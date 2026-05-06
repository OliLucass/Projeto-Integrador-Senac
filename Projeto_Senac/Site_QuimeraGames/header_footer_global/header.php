
<header class="topo-universal">
  <div class="topo-esquerda">
    <a href="<?php echo $link_home; ?>"><img class="logo" src="../imagens/logo.png" alt="Logo"></a>
    <a href="<?php echo $link_home; ?>" style="text-decoration: none;"><button class="btn-nav active">Loja</button></a>
  </div>

  <div class="topo-direita">
    <?php if ($logado): ?>
      <div style="position: relative; display: inline-block;">
        <button type="button" class="btn-icon" onclick="window.location.href='../Usuario_Logado/carrinho.php'">🛒</button>
        <?php if (isset($qtd_carrinho) && $qtd_carrinho > 0): ?>
          <span class="badge-bolinha"
            style="position: absolute; top: -8px; right: -12px; pointer-events: none;"><?php echo $qtd_carrinho; ?></span>
        <?php endif; ?>
      </div>
      <?php
      // Busca o saldo atualizado sempre que o cabeçalho carregar
      $saldo_header = 0;
      if (isset($_SESSION['id_user'])) {
        $stmt_h = $pdo->prepare("SELECT coins FROM cadastro WHERE id_user = ?");
        $stmt_h->execute([$_SESSION['id_user']]);
        $saldo_header = (int) $stmt_h->fetchColumn();
      }
      ?>

      <?php if (isset($_SESSION['id_user'])): ?>
        <div class="coin-tooltip-container">
          <div class="coin-container" id="box-coins" onclick="toggleMoedasMobile()">
            <span class="coin-icon">🪙</span>
            <span id="coin-counter"><?php echo $saldo_header; ?></span>
          </div>
          <div class="coin-tooltip">
            <strong>Como funcionam as Moedas?</strong><br><br>
            🪙 A cada <strong>R$ 1,00</strong> gasto, você ganha <strong>1 Coin</strong>.<br>
            💰 Cada <strong>1 Coin</strong> equivale a <strong>R$ 0,01</strong> de desconto na sua próxima compra!<br>
            <em>(Fique de olho nos mascotes pela loja para ganhar moedas grátis!)</em>
          </div>
        </div>
      <?php endif; ?>

      <div class="user-box" onclick="toggleMenu()">

        <img src="<?php echo !empty($usuario['url_foto'])
          ? '../uploads/' . $usuario['url_foto'] . '?v=' . time()
          : '../imagens/aidento.jpg'; ?>" class="user-img">

        <span class="user-nome"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>

        <div id="user-menu" class="user-menu">
          <a href="../Conta/conta.php">Conta</a>
          <a href="../Pagamento/pagamento.php">Pagamento</a>
          <a href="../Usuario_Logado/wishlist.php">
            Lista de desejo
            <?php if ($qtd_wishlist > 0): ?>
              <span class="badge-bolinha"><?php echo $qtd_wishlist; ?></span>
            <?php endif; ?>
          </a>
          <a href="../Usuario_Logado/meus_pedidos.php">Meus Pedidos</a>
          <a href="../Usuario_Logado/logout.php">Sair</a>
        </div>
      </div>
    <?php else: ?>
      <a href="../Entrar/Entrar.php" style="text-decoration: none;"><button class="btn-login">Entrar</button></a>
    <?php endif; ?>

    <a href="../Sac/Suporte.php" style="text-decoration: none;"><button class="btn-login">Suporte</button></a>
  </div>
</header>

<script>
function toggleMoedasMobile() {
    // Só ativa a função se estiver na tela de celular pequeno (onde os números somem)
    if (window.innerWidth <= 400) {
        const counter = document.getElementById('coin-counter');
        if (counter) {
            counter.classList.toggle('mostrar-mobile');
            
            // Oculta o balão automaticamente após 3 segundos para não atrapalhar
            setTimeout(() => {
                counter.classList.remove('mostrar-mobile');
            }, 3000);
        }
    }
}
</script>
