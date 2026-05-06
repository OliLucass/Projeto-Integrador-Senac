<?php
session_start();
require_once '../conexa.php';

$logado = isset($_SESSION['usuario_nome']);
$id_user = $_SESSION['id_user'] ?? 0;
$link_home = $logado ? '../Usuario_Logado/usuariologado.php' : '../Index/index.php';

// --- SISTEMA DE SORTEIO DE COIN (GAMIFICAÇÃO) ---
$spawn_animal = false;
if ($logado && rand(1, 100) <= 100) { 
    $animais = ['B.png', 'L.png', 'C.png', 'D.png'];
    $spawn_animal = '../imagens/' . $animais[array_rand($animais)];
}

// CONTAGEM PARA OS BADGES E DADOS DO USUÁRIO
$qtd_carrinho = 0;
$qtd_wishlist = 0;
$usuario = ['url_foto' => '', 'coins' => 0];

if ($id_user > 0) {
    // Busca foto e moedas
    $stmt_u = $pdo->prepare("SELECT url_foto, coins FROM cadastro WHERE id_user = ?");
    $stmt_u->execute([$id_user]);
    $usuario = $stmt_u->fetch(PDO::FETCH_ASSOC);
    $saldo_coins = (int) ($usuario['coins'] ?? 0);

    // Badges do Header
    $stmt_c = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_c->execute([$id_user]);
    $qtd_carrinho = $stmt_c->fetchColumn();

    $stmt_w = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_w->execute([$id_user]);
    $qtd_wishlist = $stmt_w->fetchColumn();
}

// DADOS DO JOGO
$preco_jogo = "0,00";
$preco_raw = 0;
$imagem_jogo = "../imagens/logo.png";
$titulo_jogo = "Jogo não encontrado";

if (isset($_GET['id_jogo'], $_GET['preco'])) {
    $id_jogo = (int) $_GET['id_jogo'];
    $preco_raw = (float) $_GET['preco'];
    $preco_jogo = number_format($preco_raw, 2, ',', '.');

    $_SESSION['preco_compra'] = $preco_raw;
    $_SESSION['id_jogo_compra'] = $id_jogo;

    $stmt = $pdo->prepare("SELECT titulo, Imagens_jogos FROM jogos WHERE id_play = :id");
    $stmt->execute([':id' => $id_jogo]);
    if ($jogo = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagem_jogo = $jogo['Imagens_jogos'];
        $titulo_jogo = $jogo['titulo'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Pagamento - QuimeraGames</title>
    <link rel="stylesheet" href="pagamento.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">

</head>

    <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
    <script>
        const PRECO_JOGO = <?php echo number_format($preco_raw, 2, '.', ''); ?>;
        const SALDO_COINS_INICIAL = <?php echo $saldo_coins; ?>;
    </script>

<body>
    <header><?php include '../header_footer_global/header.php'; ?></header>

    <div id="gamificacao-spawn" data-img="<?php echo $spawn_animal ? $spawn_animal : ''; ?>" style="display: none;">
    </div>

    <div class="container">
        <main class="main-pagamento">
            <div class="col-dados">
                <h1>Configurações de pagamento</h1>

                <div class="saldo-container">
                    <span class="label-saldo">Você está comprando</span>
                    <h2 class="valor-saldo"><?php echo htmlspecialchars($titulo_jogo); ?></h2>

                    <div style="margin: 20px 0;">
                        <span id="preco-exibicao" style="font-size:2.5rem; color:#e50914; font-weight:bold;">
                            R$ <?php echo $preco_jogo; ?>
                        </span>

                        <div id="box-usar-moedas"
                            style="display: <?php echo $saldo_coins > 0 ? 'block' : 'none'; ?>; margin-top: 15px; background: rgba(255,215,0,0.1); border: 1px dashed rgba(255,215,0,0.5); padding: 15px; border-radius: 8px; cursor: pointer;">
                            <label
                                style="cursor: pointer; display: flex; align-items: center; gap: 10px; color: #ffd700; font-weight: bold; font-size: 1.1rem; width: 100%; height: 100%;">
                                <input type="checkbox" id="chk-usar-coins"
                                    style="transform: scale(1.5); accent-color: #ffd700; cursor: pointer;">
                                <span id="txt-usar-coins">
                                    Usar minhas <?php echo $saldo_coins; ?> moedas
                                    (Desconto de R$ <?php echo number_format($saldo_coins * 0.01, 2, ',', '.'); ?>)
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="msg-compra-gratis"
                    style="display:none; background:#052e16; border:1px solid #22c55e; padding:20px; border-radius:8px; text-align:center;">
                    <h3 style="color:#22c55e; margin-bottom:10px;">✨ Compra Coberta pelas Moedas!</h3>
                    <p>Você tem moedas suficientes para resgatar este jogo de graça.</p>
                    <button type="button" class="btn-finalizar" style="background:#22c55e; margin-top:15px;"
                        onclick="fluxoCoins()">Resgatar Jogo Agora</button>
                </div>

                <div id="form-pagamento" class="metodos-pagamento">
                    <h3>Selecione o método de pagamento</h3>
                    <label class="metodo-card" id="mc-cartao"><input type="radio" name="metodo" value="cartao"
                            checked><span class="icon-placeholder">💳</span><span>Cartão de crédito</span></label>
                    <label class="metodo-card" id="mc-pix"><input type="radio" name="metodo" value="pix"><span
                            class="icon-placeholder">💠</span><span>Pix</span></label>
                    <label class="metodo-card" id="mc-boleto"><input type="radio" name="metodo" value="boleto"><span
                            class="icon-placeholder">📄</span><span>Boleto</span></label>

                    <div id="dados-cartao" class="form-dinamico ativo">
                        <input type="text" placeholder="Número do cartão" class="input-form" id="num-cartao"
                            maxlength="19">
                        <span class="error-msg" id="err-num">Número do cartão inválido</span>
                        <input type="text" placeholder="Nome impresso no cartão" class="input-form" id="nome-cartao">
                        <span class="error-msg" id="err-nome">Informe o nome igual ao cartão</span>
                        <div class="linha-inputs">
                            <input type="text" placeholder="MM/AA" class="input-form" id="val-cartao" maxlength="5">
                            <input type="text" placeholder="CVV" class="input-form" id="cvv-cartao" maxlength="3">
                        </div>
                        <span class="error-msg" id="err-valcvv">Data de validade ou CVV inválido</span>
                    </div>
                    <div id="dados-pix" class="form-dinamico">
                        <p class="texto-info">Clique em <strong>Finalizar Compra</strong> para gerar o QR Code Pix.</p>
                    </div>
                    <div id="dados-boleto" class="form-dinamico">
                        <p class="texto-info">Clique em <strong>Finalizar Compra</strong> para gerar o boleto.</p>
                    </div>
                    <button type="button" class="btn-finalizar" onclick="iniciarPagamento()">Finalizar Compra</button>
                </div>
            </div>

            <div class="col-logo"><img src="<?php echo $imagem_jogo; ?>" class="imagem-jogo"></div>
        </main>
    </div>

    <div class="overlay" id="ov-cartao">
        <div class="modal">
            <div class="steps" id="steps-cartao">
                <div class="step active" id="s1">1</div>
                <div class="step-line" id="sl1"></div>
                <div class="step" id="s2">2</div>
                <div class="step-line" id="sl2"></div>
                <div class="step" id="s3">3</div>
            </div>
            <div id="cartao-body"></div>
        </div>
    </div>
    <div class="overlay" id="ov-pix">
        <div class="modal">
            <div id="pix-body"></div>
        </div>
    </div>
    <div class="overlay" id="ov-boleto">
        <div class="modal">
            <div id="boleto-body"></div>
        </div>
    </div>

    <?php include '../header_footer_global/footer.php'; ?>
    <script src="pagamento.js?v=<?php echo time(); ?>"></script>
</body>

</html>
