<header class="topo-universal">
    <div class="topo-esquerda">
      <a href="<?php echo $link_home; ?>"><img class="logo" src="../imagens/logo.png" alt="Logo"></a>
      <a href="<?php echo $link_home; ?>" style="text-decoration: none;"><button
          class="btn-nav active">Loja</button></a>
    </div>

    <div class="topo-direita">
      <?php if ($logado): ?>
        <div style="position: relative; display: inline-block;">
          <button type="button" class="btn-icon"
            onclick="window.location.href='../Usuario_Logado/carrinho.php'">🛒</button>
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
            <a href="../Usuario_Logado/wishlist.php">
              Lista de desejo
              <?php if (isset($qtd_wishlist) && $qtd_wishlist > 0): ?>
                <span class="badge-bolinha"><?php echo $qtd_wishlist; ?></span>
              <?php endif; ?>
            </a>
            <a href="../Usuario_Logado/logout.php">Sair</a>
          </div>
        </div>
      <?php else: ?>
        <a href="../Entrar/Entrar.php" style="text-decoration: none;"><button class="btn-login">Entrar</button></a>
      <?php endif; ?>

      <a href="../Sac/Suporte.php" style="text-decoration: none;"><button class="btn-login">Suporte</button></a>
    </div>
  </header>