<?php
session_start();
require_once '../conexa.php';

// 1. Proteção de Login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../Entrar/Entrar.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$email_user = $_SESSION['usuario_email'] ?? '';
$logado = true;
$link_home = '../Usuario_Logado/usuariologado.php';

// 2. BUSCA FOTO DO USUÁRIO E CONTADORES (Faltava aqui)
$usuario = ['url_foto' => ''];
$qtd_carrinho = 0;
$qtd_wishlist = 0;

// Busca Foto
$stmt_u = $pdo->prepare("SELECT url_foto FROM cadastro WHERE email = ?");
$stmt_u->execute([$email_user]);
$usuario = $stmt_u->fetch(PDO::FETCH_ASSOC);

// Conta Carrinho
$stmt_c = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
$stmt_c->execute([$id_user]);
$qtd_carrinho = $stmt_c->fetchColumn();

// Conta Lista de Desejos
$stmt_w = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
$stmt_w->execute([$id_user]);
$qtd_wishlist = $stmt_w->fetchColumn();

// 3. Consulta da Biblioteca
$query = "SELECT j.* FROM jogos j 
          INNER JOIN minha_biblioteca mb ON j.id_play = mb.id_play 
          WHERE mb.id_user = :u";
$stmt = $pdo->prepare($query);
$stmt->execute(['u' => $id_user]);
$biblioteca = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nomes_categorias = [
    1 => 'Ação',
    2 => 'Aventura',
    3 => 'Corrida',
    4 => 'Estratégia',
    5 => 'Esporte',
    6 => 'FPS',
    7 => 'Luta',
    8 => 'Terror',
    9 => 'Sobrevivência',
    10 => 'RPG'
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Biblioteca</title>
    <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
</head>

<body>

    <?php include '../header_footer_global/header.php'; ?>


    <main class="container" style="margin-top: 100px; min-height: 70vh;">

        <div class="header-biblioteca">
            <h2>Minha Biblioteca</h2>
            <div class="filtro-biblioteca">
                <label for="filtro-genero" style="margin-right: 10px; color: #aaa;">Filtrar por:</label>
                <select id="filtro-genero" onchange="filtrarJogos()">
                    <option value="Todos">Todos os Jogos</option>
                    <option value="Ação">Ação</option>
                    <option value="Aventura">Aventura</option>
                    <option value="Corrida">Corrida</option>
                    <option value="Estratégia">Estratégia</option>
                    <option value="Esporte">Esporte</option>
                    <option value="FPS">FPS</option>
                    <option value="Luta">Luta</option>
                    <option value="Terror">Terror</option>
                    <option value="Sobrevivência">Sobrevivência</option>
                    <option value="RPG">RPG</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
        </div>

        <div class="lista-pedidos" id="container-jogos">
            <?php foreach ($biblioteca as $p):
                // Descobre o nome do gênero ou chama de 'Outros'
                $genero = isset($nomes_categorias[$p['id_categoria']]) ? $nomes_categorias[$p['id_categoria']] : 'Outros';
                ?>
                <div class="pedido-card" data-genero="<?= htmlspecialchars($genero) ?>">
                    <img src="<?= htmlspecialchars($p['Imagens_jogos']) ?>" class="capa-jogo">

                    <div class="info-jogo">
                        <h3><?= htmlspecialchars($p['titulo']) ?></h3>

                        <div class="plataforma-info-bib">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" width="18"
                                alt="Steam">
                            <span>Sistema Operacional: <strong>Windows</strong></span>
                        </div>

                        <a href="https://store.steampowered.com/" target="_blank" class="btn-resgatar">🔑 Resgatar
                            Código</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($biblioteca)): ?>
                <p style="color: #aaa; margin-top: 20px;">Você ainda não possui jogos na sua biblioteca.</p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function filtrarJogos() {
            const filtro = document.getElementById('filtro-genero').value;
            const cards = document.querySelectorAll('.pedido-card');

            cards.forEach(card => {
                const generoJogo = card.getAttribute('data-genero');
                // Se escolheu 'Todos' ou se o gênero bater, ele mostra. Senão, esconde.
                if (filtro === 'Todos' || generoJogo === filtro) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    <script src="../Usuario_Logado/script.js" defer></script>
    <?php include '../header_footer_global/footer.php'; ?>

</body>

</html>
