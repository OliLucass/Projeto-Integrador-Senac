<div id="user-menu-2" class="user-menu">
    <a href="../Conta/conta.php">Conta</a>
    <a href="../Pagamento/pagamento.php">Pagamento</a>
    <a href="../Usuario_Logado/wishlist.php">
        Lista de desejo
        <?php if (isset($qtd_wishlist) && $qtd_wishlist > 0): ?>
            <span class="badge-bolinha"><?php echo $qtd_wishlist; ?></span>
        <?php endif; ?>
    </a>
    <a href="../Usuario_Logado/meus_pedidos.php">Meus Pedidos</a>
    <a href="../Usuario_Logado/logout.php">Sair</a>
</div>
