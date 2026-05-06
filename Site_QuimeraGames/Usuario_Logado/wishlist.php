<?php
session_start();
require_once '../conexa.php';

// 1. Definições básicas de sessão
$logado = isset($_SESSION['usuario_nome']);
$id_user = $_SESSION['id_user'] ?? 0;
$email_user = $_SESSION['usuario_email'] ?? '';

$usuario = [];

if ($id_user > 0) {
    $stmt_user = $pdo->prepare("SELECT url_foto FROM cadastro WHERE id_user = ?");
    $stmt_user->execute([$id_user]);
    $usuario = $stmt_user->fetch(PDO::FETCH_ASSOC) ?? [];
}

$qtd_carrinho = 0;
$qtd_wishlist = 0;
$usuario = ['url_foto' => ''];

if ($logado && $id_user > 0) {
    // 3. BUSCA OS DADOS ATUALIZADOS DO USUÁRIO (Essencial para a foto de perfil)
    $stmt_u = $pdo->prepare("SELECT * FROM cadastro WHERE email = ?");
    $stmt_u->execute([$email_user]);
    $usuario = $stmt_u->fetch(PDO::FETCH_ASSOC);

    // 4. CONTAGEM PARA O BADGE DO CARRINHO
    $stmt_c = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_c->execute([$id_user]);
    $qtd_carrinho = $stmt_c->fetchColumn();

    // 5. CONTAGEM PARA O BADGE DA LISTA DE DESEJOS
    $stmt_w = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_w->execute([$id_user]);
    $qtd_wishlist = $stmt_w->fetchColumn();
}

// 6. Lógica específica da página (exemplo para a Wishlist)
$query_itens = "SELECT j.* FROM jogos j INNER JOIN lista_desejos ld ON j.id_play = ld.id_play WHERE ld.id_user = :u";
$stmt_itens = $pdo->prepare($query_itens);
$stmt_itens->execute(['u' => $id_user]);
$itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

$link_home = $logado ? 'usuariologado.php' : '../Index/index.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Lista de Desejos - QuimeraGames</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="wishlist_carrinho.css">
    <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
</head>

<body>

    <header>
        <?php include '../header_footer_global/header.php'; ?>
    </header>

    <div class="wish-container">
        <h2>Minha lista de desejo</h2>

        <?php if (empty($itens)): ?>
            <div class="empty-state">
                <span class="sad-icon">☹️</span>
                <h3>Você ainda não adicionou nada na sua lista de desejo</h3>
                <a href="usuariologado.php" class="btn-red">Explorar jogos</a>
            </div>
        <?php else: ?>
            <?php foreach ($itens as $j): ?>
                <div class="wish-item">
                    <img class="capa" src="<?= htmlspecialchars($j['Imagens_jogos']) ?>">

                    <div class="wish-info">
                        <small>Chave de ativação</small>
                        <h3><?= htmlspecialchars($j['titulo']) ?></h3>
                        <div class="plataforma-info">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" width="14">
                            <span>Windows</span>
                        </div>
                    </div>

                    <div class="wish-price">R$ <?= number_format($j['Valor'], 2, ',', '.') ?></div>

                    <div class="wish-actions">
                        <a href="acoes_cliente.php?id=<?= $j['id_play'] ?>&acao=del_wishlist">Remover</a>
                        <a href="acoes_cliente.php?id=<?= $j['id_play'] ?>&acao=add_carrinho">Adicionar no carrinho</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include '../header_footer_global/footer.php'; ?>
    <script src="../Usuario_Logado/script.js" defer></script>
</body>

</html>
