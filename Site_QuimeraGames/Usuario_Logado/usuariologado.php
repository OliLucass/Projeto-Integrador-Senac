<?php
session_start();

// se não estiver logado, volta pro login
if (!isset($_SESSION['usuario_nome'])) {
  header("Location: ../Entrar/Entrar.php");
  exit;
}

require_once '../conexa.php';

// CONTAGEM PARA OS BADGES (Cole isso no topo dos seus arquivos PHP)
$qtd_carrinho = 0;
$qtd_wishlist = 0;
if (isset($_SESSION['id_user'])) {
  $stmt_cart = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
  $stmt_cart->execute([$_SESSION['id_user']]);
  $qtd_carrinho = $stmt_cart->fetchColumn();

  $stmt_wish = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
  $stmt_wish->execute([$_SESSION['id_user']]);
  $qtd_wishlist = $stmt_wish->fetchColumn();
}
$logado = isset($_SESSION['usuario_nome']);
$link_home = $logado ? '../Usuario_Logado/usuariologado.php' : '../Index/index.php';

if (!isset($pdo)) {
  die("Erro: A variável de conexão \$pdo não foi encontrada no conexa.php.");
}

try {
  $semana_atual = (int) date('W');
  $id_user_logado = $_SESSION['id_user'] ?? 0;

  $stmt_carousel = $pdo->prepare("SELECT * FROM jogos ORDER BY MOD(id_play, :semana) LIMIT 7");
  $stmt_carousel->bindValue(':semana', $semana_atual, PDO::PARAM_INT);
  $stmt_carousel->execute();
  $jogos_carousel = $stmt_carousel->fetchAll(PDO::FETCH_ASSOC);

  $stmt_descontos = $pdo->prepare("SELECT * FROM jogos ORDER BY MOD(id_play, :semana_plus) LIMIT 6");
  $stmt_descontos->bindValue(':semana_plus', ($semana_atual + 2), PDO::PARAM_INT);
  $stmt_descontos->execute();
  $jogos_descontos = $stmt_descontos->fetchAll(PDO::FETCH_ASSOC);

  $stmt_categorias = $pdo->prepare("SELECT id_categoria, MIN(Imagens_jogos) as capa FROM jogos GROUP BY id_categoria");
  $stmt_categorias->execute();
  $categorias_bd = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

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
} catch (PDOException $e) {
  die("Erro na consulta ao banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuimeraGames - logado</title>
  <link rel="stylesheet" href="../Usuario_Logado/style.css">
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

  <div class="container">

    <div class="menu-wrapper" style="position: relative; z-index: 1000;">
      <nav class="menu-busca">
        <div class="busca-input">
          <span>🔍</span>
          <input type="text" name="query" placeholder="Pesquisar loja" required>
        </div>
        <button class="btn-dropdown" id="btn-explorar">Explorar ▾</button>
        <button class="btn-dropdown" id="btn-categorias">Categorias ▾</button>
      </nav>

      <div id="resultados-busca"
        style="display: none; width: 100%; max-width: 1400px; margin: 0 auto; padding: 20px; color: white;"></div>

      <div id="painel-explorar" class="painel-dropdown">
        <div class="banner-explorar-container">
          <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070" alt="Banner Explorar"
            class="img-banner-explorar">
          <div class="overlay-banner"></div>
          <a href="../mais_vendidos/Index.php" class="btn-mais-vendidos-banner">Mais vendidos</a>
        </div>
      </div>

      <div id="painel-categorias" class="painel-dropdown">
        <h3 class="titulo-painel">Gêneros Populares</h3>
        <div class="carousel-categorias-wrapper">
          <button class="seta-cat esquerda" id="seta-esquerda">&#10094;</button>
          <div class="categorias-painel-grid" id="grid-categorias">
            <?php foreach ($categorias_bd as $cat): ?>
              <?php $nome_cat = isset($nomes_categorias[$cat['id_categoria']]) ? $nomes_categorias[$cat['id_categoria']] : 'Outros'; ?>

              <a href="../Categorias/categoria.php?id=<?php echo $cat['id_categoria']; ?>" class="card-cat-item"
                style="text-decoration: none; color: inherit;">
                <div class="img-cat-wrapper">
                  <img src="<?php echo htmlspecialchars($cat['capa']); ?>" alt="<?php echo $nome_cat; ?>">
                </div>
                <span><?php echo $nome_cat; ?></span>
              </a>
            <?php endforeach; ?>
          </div>
          <button class="seta-cat direita" id="seta-direita">&#10095;</button>
        </div>
      </div>
    </div>

    <div class="carousel-container">
      <?php foreach ($jogos_carousel as $i => $radio): ?>
        <input type="radio" name="slide" id="s<?php echo $i + 1; ?>" <?php echo ($i == 0) ? 'checked' : ''; ?>>
      <?php endforeach; ?>

      <div class="slides">
        <?php foreach ($jogos_carousel as $i => $jogo): ?>
          <div class="slide" id="slide<?php echo $i + 1; ?>">
            <div class="content-box">
              <div class="poster-box">
                <a href="../Tela_Jogo/index_jogo.php?id=<?php echo $jogo['id_play']; ?>">
                  <img src="<?php echo htmlspecialchars($jogo['Imagens_jogos']); ?>" id="mainImg<?php echo $i + 1; ?>"
                    data-capa="<?php echo $jogo['Imagens_jogos']; ?>" class="capa-poster">
                </a>
              </div>

              <div class="info-box" id="infoBox<?php echo $i + 1; ?>">
                <span class="label-disponivel">JÁ DISPONÍVEL</span>
                <h2 class="titulo-jogo"><?php echo htmlspecialchars($jogo['titulo']); ?></h2>
                <p class="descricao-jogo"><?php echo mb_strimwidth($jogo['informacoes'], 0, 180, "..."); ?></p>

                <div class="card-plataforma" style="margin-bottom: 20px;">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" width="16">
                  <span>Windows</span>
                </div>

                <div class="precos-container">
                  <?php if ($jogo['Valor'] > 0): ?>
                    <div class="col-precos">
                      <span class="v-novo">R$ <?php echo number_format($jogo['Valor'], 2, ',', '.'); ?></span>
                    </div>
                  <?php else: ?>
                    <span class="v-gratis">Gratuito</span>
                  <?php endif; ?>
                </div>
                <a href="../Tela_Jogo/index_jogo.php?id=<?php echo $jogo['id_play']; ?>" style="text-decoration: none;">
                  <button class="btn-comprar-carrossel">COMPRAR AGORA</button>
                </a>
              </div>
            </div>

            <div class="side-box">
              <img src="<?php echo htmlspecialchars($jogo['Imagens_cen1']); ?>"
                onclick="gerenciarTroca('<?php echo $i + 1; ?>', this)">
              <img src="<?php echo htmlspecialchars($jogo['Imagens_cen2']); ?>"
                onclick="gerenciarTroca('<?php echo $i + 1; ?>', this)">
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="dots">
        <?php foreach ($jogos_carousel as $i => $dot): ?>
          <label for="s<?php echo $i + 1; ?>"></label>
        <?php endforeach; ?>
      </div>
    </div>

    <section class="secao">
      <h2>Descontos em destaque ></h2>
      <div class="jogos-grid">
        <?php foreach ($jogos_descontos as $jogo): ?>
          <a href="../Tela_Jogo/index_jogo.php?id=<?php echo $jogo['id_play']; ?>&desconto=1"
            style="text-decoration: none; color: inherit; display: block;">
            <div class="card-jogo-container">
              <div class="thumb-wrapper">
                <img src="<?php echo htmlspecialchars($jogo['Imagens_jogos']); ?>">
                <?php if ($jogo['Valor'] > 0): ?>
                  <span class="badge-desconto">-10%</span>
                <?php endif; ?>
              </div>
              <div class="card-info-texto">
                <h4><?php echo htmlspecialchars($jogo['titulo']); ?></h4>
                <div class="card-plataforma">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" width="16">
                  <span>Windows</span>
                </div>
                <div class="precos-card">
                  <?php if ($jogo['Valor'] > 0): ?>
                    <span class="v-old">R$ <?php echo number_format($jogo['Valor'], 2, ',', '.'); ?></span>
                    <span class="v-new">R$ <?php echo number_format($jogo['Valor'] * 0.90, 2, ',', '.'); ?></span>
                  <?php else: ?>
                    <span class="v-gratis">Gratuito</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

  </div>



  <script src="../Usuario_Logado/script.js" defer></script>
  <script>
    function toggleMenu() {
      const menu = document.getElementById("user-menu");
      if (menu) menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    }
    document.addEventListener("click", function (e) {
      const userBox = document.querySelector(".user-box");
      const menu = document.getElementById("user-menu");
      if (userBox && menu && !userBox.contains(e.target)) {
        menu.style.display = "none";
      }
    });
  </script>
  <footer class="rodape-universal">QuimeraGames &copy; 2026</footer>
</body>

</html>