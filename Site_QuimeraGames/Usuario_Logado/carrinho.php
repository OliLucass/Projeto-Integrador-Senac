<?php
session_start();
require_once '../conexa.php';

$id_user = $_SESSION['id_user'] ?? 0;

// CONTAGEM PARA OS BADGES
$qtd_carrinho = 0;
$qtd_wishlist = 0;
if ($id_user > 0) {
    $stmt_conta_cart = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_conta_cart->execute([$id_user]);
    $qtd_carrinho = $stmt_conta_cart->fetchColumn();

    $stmt_conta_wish = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_conta_wish->execute([$id_user]);
    $qtd_wishlist = $stmt_conta_wish->fetchColumn();
}

$query = "SELECT j.* FROM jogos j INNER JOIN carrinho c ON j.id_play = c.id_play WHERE c.id_usuario = :u";
$stmt = $pdo->prepare($query);
$stmt->execute(['u' => $id_user]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
$logado = isset($_SESSION['usuario_nome']);
$link_home = $logado ? 'usuariologado.php' : '../Index/index.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho - QuimeraGames</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="wishlist_carrinho.css">
</head>

<body>

    <header class="topo-universal">
        <div class="topo-esquerda">
            <a href="<?php echo $link_home; ?>"><img class="logo" src="../imagens/logo.png" alt="Logo"></a>
            <a href="<?php echo $link_home; ?>" style="text-decoration: none;"><button
                    class="btn-nav active">Loja</button></a>
        </div>

        <div class="topo-direita">
            <?php if ($logado): ?>
                <div style="position: relative; display: inline-block;">
                    <button type="button" class="btn-icon" onclick="window.location.href='carrinho.php'">🛒</button>
                    <?php if (isset($qtd_carrinho) && $qtd_carrinho > 0): ?>
                        <span class="badge-bolinha"
                            style="position: absolute; top: -8px; right: -12px; pointer-events: none;"><?php echo $qtd_carrinho; ?></span>
                    <?php endif; ?>
                </div>

                <div class="user-box" onclick="toggleMenu()">
                    <img src="../imagens/aidento.jpg" class="user-img" alt="Avatar">
                    <span class="user-nome"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>

                    <div id="user-menu" class="user-menu">
                        <a href="../Conta/conta.php">Conta</a>
                        <a href="../Pagamento/pagamento.php">Pagamento</a>
                        <a href="wishlist.php">
                            Lista de desejo
                            <?php if (isset($qtd_wishlist) && $qtd_wishlist > 0): ?>
                                <span class="badge-bolinha"><?php echo $qtd_wishlist; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="logout.php">Sair</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../Entrar/Entrar.php" style="text-decoration: none;"><button class="btn-login">Entrar</button></a>
            <?php endif; ?>
            <a href="../Sac/Suporte.php" style="text-decoration: none;"><button class="btn-login">Suporte</button></a>
        </div>
    </header>

    <div class="cart-layout">
        <div class="cart-list">
            <h2>Meu Carrinho</h2>
            <?php if (empty($itens)): ?>
                <div class="empty-state">
                    <span class="sad-icon">☹️</span>
                    <h3>Seu carrinho ainda não possui nenhum jogo</h3>
                    <a href="usuariologado.php" class="btn-red">Comprar jogos</a>
                </div>
            <?php else: ?>
                <?php foreach ($itens as $j):
                    $total += $j['Valor']; ?>
                    <div class="cart-item">
                        <img class="capa" src="<?= htmlspecialchars($j['Imagens_jogos']) ?>">
                        <div class="cart-info">
                            <h3><?= htmlspecialchars($j['titulo']) ?></h3>
                            <small>R$ <?= number_format($j['Valor'], 2, ',', '.') ?></small>
                        </div>
                        <a href="acoes_cliente.php?id=<?= $j['id_play'] ?>&acao=del_carrinho" class="remover-link">Remover</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($itens)):
            $id_checkout = $itens[0]['id_play']; ?>
            <div class="cart-summary">
                <h3>Resumo de jogos</h3>
                <div class="summary-row"><span>Preço</span> <span>R$ <?= number_format($total, 2, ',', '.') ?></span></div>
                <hr style="opacity: 0.1; margin: 20px 0;">
                <div class="summary-row" style="font-weight: bold; font-size: 19px;"><span>Subtotal</span> <span>R$
                        <?= number_format($total, 2, ',', '.') ?></span></div>
                <button class="btn-checkout"
                    onclick="window.location.href='../Pagamento/pagamento.php?id_jogo=<?= $id_checkout ?>&preco=<?= $total ?>'">Finalizar
                    compra</button>
            </div>
        <?php endif; ?>
    </div>

    <footer class="rodape-universal">QuimeraGames &copy; 2026</footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById("user-menu");
            if (menu) menu.style.display = menu.style.display === "flex" ? "none" : "flex";
        }
        document.addEventListener("click", function (e) {
            const userBox = document.querySelector(".user-box");
            const menu = document.getElementById("user-menu");
            if (userBox && menu && !userBox.contains(e.target)) menu.style.display = "none";
        });
    </script>

</body>

</html>