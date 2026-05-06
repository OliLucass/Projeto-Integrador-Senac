<?php
session_start();
require_once '../conexa.php';

// 3. Definições de sessão e badges
$logado = isset($_SESSION['usuario_nome']);
$id_user_logado = $_SESSION['id_user'] ?? 0;
$email_user = $_SESSION['usuario_email'] ?? '';

// 1. Pega o ID do jogo da URL
$id_jogo = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// --- SISTEMA DE SORTEIO DE COIN (GAMIFICAÇÃO) ---
$spawn_animal = false;
if ($logado) {
    $chance = rand(1, 100);
    // 30% de chance de aparecer um mascote
    if ($chance <= 100) {
       $animais = ['bode.png', 'leao.png', 'cobra.png', 'dragao.png'];
        $animal_sorteado = $animais[array_rand($animais)];
        // Caminho da imagem (ajuste para a pasta onde você salvou as fotos deles)
        $spawn_animal = '../imagens/' . $animal_sorteado;
    }
}
// ------------------------------------------------

if ($id_jogo === 0) {
    die("<h2 style='color:black; text-align:center; margin-top:50px; font-family:sans-serif;'>Jogo não encontrado na URL. Volte para a loja.</h2>");
}

// 2. Consulta a média das avaliações
try {
    $stmt_media = $pdo->prepare("SELECT AVG(nota) as media FROM avaliacao WHERE id_play = :id");
    $stmt_media->bindValue(':id', $id_jogo, PDO::PARAM_INT);
    $stmt_media->execute();
    $dados = $stmt_media->fetch(PDO::FETCH_ASSOC);
    $media_nota = ($dados && $dados['media'] !== null) ? number_format((float) $dados['media'], 1, '.', '') : '0.0';
} catch (PDOException $e) {
    $media_nota = '0.0';
}

$link_home = $logado ? '../Usuario_Logado/usuariologado.php' : '../Index/index.php';
$veio_do_desconto = isset($_GET['desconto']) && $_GET['desconto'] == '1';

// Variáveis essenciais para o Header funcionar!
$qtd_carrinho = 0;
$qtd_wishlist = 0;
$ta_na_lista = false;
$usuario = ['url_foto' => ''];

if ($logado && $id_user_logado > 0) {
    // Busca a foto do usuário com segurança
    $stmt_user = $pdo->prepare("SELECT url_foto FROM cadastro WHERE email = :email");
    $stmt_user->bindParam(":email", $email_user);
    $stmt_user->execute();
    $usuario = $stmt_user->fetch(PDO::FETCH_ASSOC) ?: $usuario;

    // Conta Carrinho
    $stmt_cart = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_cart->execute([$id_user_logado]);
    $qtd_carrinho = $stmt_cart->fetchColumn();

    // Conta Wishlist
    $stmt_wish = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_wish->execute([$id_user_logado]);
    $qtd_wishlist = $stmt_wish->fetchColumn();

    // Verifica se este jogo está na lista de desejos
    $stmt_check_lista = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ? AND id_play = ?");
    $stmt_check_lista->execute([$id_user_logado, $id_jogo]);
    if ($stmt_check_lista->fetchColumn() > 0) {
        $ta_na_lista = true;
    }
}

// 4. Busca os dados do jogo
try {
    $query = "SELECT j.*, c.tipo_categoria 
              FROM jogos j 
              LEFT JOIN categorias c ON j.id_categoria = c.id_categoria 
              WHERE j.id_play = :id";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $id_jogo, PDO::PARAM_INT);
    $stmt->execute();
    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$jogo) {
        die("<h2 style='color:black; text-align:center; margin-top:50px; font-family:sans-serif;'>Jogo não encontrado no banco de dados.</h2>");
    }

    $trailer_url = isset($jogo['Trailers']) ? $jogo['Trailers'] : '';
    if (!empty($trailer_url) && strpos($trailer_url, 'drive.google.com/file/d/') !== false) {
        $trailer_url = preg_replace('/\/view.*$/', '/preview', $trailer_url);
    }

    $valor_original = (float) $jogo['Valor'];

    // Lógica de desconto automático
    if ($valor_original < 100) {
        // Menor que 100: 5% de desconto
        $valor_venda = $valor_original * 0.95;
    } else {
        // 100 ou mais: 10% de desconto
        $valor_venda = $valor_original * 0.90;
    }
    $cupom_sugerido = ($valor_original < 100) ? 'QUIMERA5' : 'QUIMERA10';
    $tem_desconto = $veio_do_desconto;
    $valor_venda = $tem_desconto ? ($valor_original * 0.90) : $valor_original;

    $req_texto = isset($jogo['req_sistema']) ? $jogo['req_sistema'] : '';
    $pos_rec = strpos($req_texto, 'Recomendado');
    if ($pos_rec !== false) {
        $req_minimo = substr($req_texto, 0, $pos_rec);
        $req_recomendado = substr($req_texto, $pos_rec);
    } else {
        $req_minimo = $req_texto;
        $req_recomendado = "Informação não especificada.";
    }

} catch (PDOException $e) {
    die("<h2 style='color:black;'>Erro na consulta: " . $e->getMessage() . "</h2>");
}


    
$sql = "SELECT COUNT(*) FROM compra 
        WHERE id_user = ? AND id_play = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_user_logado, $id_jogo]);

$jaComprou = $stmt->fetchColumn() > 0;



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($jogo['titulo']); ?> - QuimeraGames</title>
    <link rel="stylesheet" href="../Css/stylles.css">
    <link rel="stylesheet" href="../Css/Styles.css">
    <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="StylesTelaJogo.css?v=<?php echo time(); ?>">
</head>

<body>

    <header style="position: relative; z-index: 999999; overflow: visible;">
        <?php include '../header_footer_global/header.php'; ?>
        <?php include '../header_footer_global/menu_usuario.php'; ?>
    </header>

    <div id="gamificacao-spawn" data-img="<?php echo $spawn_animal ? $spawn_animal : ''; ?>" style="display: none;"></div>

    <div class="container game-page-container">
        <div id="dados-sessao" data-logado="<?php echo $logado ? 'true' : 'false'; ?>"
            data-jogo="<?php echo $id_jogo; ?>" data-tem-desconto="<?php echo $tem_desconto ? 'true' : 'false'; ?>">
        </div>

        <div class="game-layout">

            <div class="game-left-col">

                <div class="main-media" id="painel-midia"
                    data-type="<?php echo !empty($trailer_url) ? 'video' : 'image'; ?>"
                    data-src="<?php echo !empty($trailer_url) ? htmlspecialchars($trailer_url) : htmlspecialchars($jogo['Imagens_jogos']); ?>">

                    <?php if (!empty($trailer_url)): ?>
                        <div id="media-video-container" style="display:block; width:100%; height:100%;">
                            <iframe id="video-iframe" src="<?php echo htmlspecialchars($trailer_url); ?>" width="100%"
                                height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                        </div>
                        <div id="media-image-container" style="display:none; width:100%; height:100%;">
                            <img id="main-image" src=""
                                style="width:100%; height:100%; object-fit:contain; background:#000;">
                        </div>
                    <?php else: ?>
                        <div id="media-video-container" style="display:none; width:100%; height:100%;">
                            <iframe id="video-iframe" src="" width="100%" height="100%" frameborder="0"
                                allow="autoplay; fullscreen" allowfullscreen></iframe>
                        </div>
                        <div id="media-image-container" style="display:block; width:100%; height:100%;">
                            <img id="main-image" src="<?php echo htmlspecialchars($jogo['Imagens_jogos']); ?>"
                                style="width:100%; height:100%; object-fit:contain; background:#000;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="media-thumbnails" id="galeria-thumbnails">
                    <?php if (!empty($trailer_url)): ?>
                        <div class="thumb-wrapper" data-type="image"
                            data-src="<?php echo htmlspecialchars($jogo['Imagens_jogos']); ?>"
                            style="cursor:pointer; position:relative;">
                            <img src="<?php echo htmlspecialchars($jogo['Imagens_jogos']); ?>" class="thumb-item" alt="Capa"
                                style="width:100%; height:100%; object-fit:cover; display:block;">
                        </div>
                    <?php endif; ?>

                    <div class="thumb-wrapper" data-type="image"
                        data-src="<?php echo htmlspecialchars($jogo['Imagens_cen1']); ?>"
                        style="cursor:pointer; position:relative;">
                        <img src="<?php echo htmlspecialchars($jogo['Imagens_cen1']); ?>" class="thumb-item"
                            alt="Cenário 1" style="width:100%; height:100%; object-fit:cover; display:block;">
                    </div>

                    <div class="thumb-wrapper" data-type="image"
                        data-src="<?php echo htmlspecialchars($jogo['Imagens_cen2']); ?>"
                        style="cursor:pointer; position:relative;">
                        <img src="<?php echo htmlspecialchars($jogo['Imagens_cen2']); ?>" class="thumb-item"
                            alt="Cenário 2" style="width:100%; height:100%; object-fit:cover; display:block;">
                    </div>
                </div>

                <div class="game-description card-moderno">
                    <h1 class="game-page-title"><?php echo htmlspecialchars($jogo['titulo']); ?></h1>
                    <p><?php echo nl2br(htmlspecialchars($jogo['informacoes'])); ?></p>
                </div>

                <div class="game-rating card-moderno">
                    <h3>Nota dos compradores da QuimeraGames</h3>
                    <p class="rating-sub">Fornecidas por compradores no ecossistema Quimera</p>
                    <div class="stars-container">
                        <span class="nota-numero"><?php echo $media_nota; ?></span>
                        <div class="happy-stars">
                            <span class="star-icon active">★</span>
                            <span class="star-icon active">★</span>
                            <span class="star-icon active">★</span>
                            <span class="star-icon active">★</span>
                            <span class="star-icon inactive">★</span>
                        </div>
                        <span id="msg-login-rating"
                            style="color:#e50914; font-size: 0.85rem; display:none; margin-left:15px;">
                            Faça login para avaliar.
                        </span>
                    </div>
                </div>

                <div class="system-requirements card-moderno">
                    <h3>Requisitos de sistema</h3>
                    <div class="req-icon"><img
                            src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" width="24">
                        Windows</div>

                    <div class="req-grid">
                        <div class="req-coluna">
                            <h4>Mínimos</h4>
                            <p><?php echo nl2br(htmlspecialchars(str_replace('Mínimo Requer: ', '', $req_minimo))); ?>
                            </p>
                        </div>
                        <div class="req-coluna">
                            <h4>Recomendados</h4>
                            <p><?php echo nl2br(htmlspecialchars(str_replace('Recomendado: ', '', $req_recomendado))); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="game-right-col">
                <div class="capa-lateral-container">
                    <img src="<?php echo htmlspecialchars($jogo['Imagens_jogos']); ?>" class="side-cover" alt="Capa">
                </div>

                <div class="buy-panel card-moderno">
                    <div class="price-box">
                        <?php if ($valor_original > 0): ?>
                            <?php if ($tem_desconto): ?>
                                <span class="badge-desconto-side">-10%</span>
                                <div class="price-values">
                                    <span class="v-old-side">R$
                                        <?php echo number_format($valor_original, 2, ',', '.'); ?></span>
                                    <span class="v-new-side" id="preco-final" data-valor="<?php echo $valor_venda; ?>">
                                        R$ <?php echo number_format($valor_venda, 2, ',', '.'); ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="v-new-side" id="preco-final" data-valor="<?php echo $valor_venda; ?>">
                                    R$ <?php echo number_format($valor_venda, 2, ',', '.'); ?>
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="v-new-side">Gratuito</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($jaComprou): ?>

                    <button class="btn-action btn-cart" id="btn-add-lista">Minha lista</button>

                        <?php else: ?>

                    <button class="btn-action btn-buy" id="btn-comprar-agora">
                        Comprar
                    </button>
                    <button class="btn-action btn-cart" id="btn-add-carrinho">Carrinho</button>

                    <button class="btn-action btn-epic-wishlist <?php echo $ta_na_lista ? 'active' : ''; ?>"id="btn-add-wishlist">

                        <?php if ($ta_na_lista): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#e62429" stroke="#e62429" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Na Lista de Desejos
                        <?php else: ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Lista de desejo
                        <?php endif; ?>
                    </button>
                            <?php endif; ?>
                    <div class="cupom-area">
                        <p class="cupom-titulo"> Sugestão de Cupom: <strong><?php echo $cupom_sugerido; ?></strong>
                        </p>
                        <div class="cupom-input-group">
                            <input type="text" id="input-cupom" placeholder="Ex: <?php echo $cupom_sugerido; ?>">
                            <button id="btn-aplicar-cupom">Aplicar</button>
                        </div>
                        <p id="msg-cupom"></p>
                    </div>

                    <p class="steam-aviso">Este produto é ativado via <strong>chave de ativação</strong></p>
                </div>

                <div class="info-table card-moderno">
                    <div class="info-row"><span>Distribuidora:</span>
                        <span><?php echo htmlspecialchars($jogo['distribuidora']); ?></span>
                    </div>
                    <div class="info-row"><span>Desenvolvedora:</span>
                        <span><?php echo htmlspecialchars($jogo['desenvolvedora']); ?></span>
                    </div>
                    <div class="info-row"><span>Lançamento:</span>
                        <span><?php echo date('d/m/Y', strtotime($jogo['data_lancamento'])); ?></span>
                    </div>
                    <div class="info-row"><span>Categoria:</span>
                        <span><?php echo htmlspecialchars($jogo['tipo_categoria']); ?></span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Esta função garante que o menu vai abrir ao clicar na foto
        function toggleMenu() {
            const menu = document.getElementById("user-menu");
            if (menu) {
                menu.style.display = menu.style.display === "flex" ? "none" : "flex";
            }
        }

        // Esta função garante que o menu fecha ao clicar fora dele
        document.addEventListener("click", function (e) {
            const userBox = document.querySelector(".user-box");
            const menu = document.getElementById("user-menu");
            if (userBox && menu && !userBox.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    </script>

    <script src="Script_jogo.js" defer></script>
    <?php include '../header_footer_global/footer.php'; ?>
</body>

</html>
