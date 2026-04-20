<?php
session_start();
require_once("../conexa.php");


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


$categoria = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;

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

try {

  if ($categoria > 0) {
    $stmt = $pdo->prepare("
            SELECT j.*, COUNT(c.id_play) as total_vendas
            FROM jogos j
            LEFT JOIN compra c ON j.id_play = c.id_play
            WHERE j.id_categoria = :categoria
            GROUP BY j.id_play
            ORDER BY total_vendas DESC
            LIMIT 20
        ");
    $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);

  } else {
    $stmt = $pdo->prepare("
            SELECT j.*, COUNT(c.id_play) as total_vendas
            FROM jogos j
            LEFT JOIN compra c ON j.id_play = c.id_play
            GROUP BY j.id_play
            ORDER BY total_vendas DESC
            LIMIT 20
        ");
  }

  $stmt->execute();
  $jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Mais Vendidos</title>
  <link rel="stylesheet" href="stylee.css">

  <style>
    /* 🔥 DROPDOWN */
    .btn-categorias {
      background: transparent;
      border: 2px solid white;
      color: white;
      padding: 10px 18px;
      border-radius: 30px;
      cursor: pointer;
      border-color: #e50914
    }

    .menu-categorias {
      position: absolute;
      top: 55px;
      margin-left: 15px;
      background: #13192b;
      border-radius: 10px;
      display: none;
      flex-direction: column;
      width: 180px;
      z-index: 999;
    }

    .menu-categorias a {
      padding: 10px;
      text-align: center;
      color: white;
      text-decoration: none;
    }

    .menu-categorias a:hover {
      background: #1f2a44;
    }

    /* GRID */
    .grid-jogos {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 25px;
      padding: 40px 200px;
    }

    /* CARD */

    .card-jogo {
      background: #0f1b35;
      border-radius: 15px;
      overflow: hidden;
      position: relative;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-jogo:hover {
      transform: scale(1.12) translateY(-5px);
      z-index: 10;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
    }

    /* IMG */
    .card-jogo img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      position: relative;
      z-index: 1;
    }

    /* HOVER SUAVE (SEM PISCAR) */

    .card-jogo:hover img {
      opacity: 1;
    }

    /* INFO */
    .card-info {
      padding: 15px;
    }

    .preco {
      margin-top: 10px;
      font-weight: bold;
      color: #00ff88;
    }

    /* BADGE */
    .badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #e50914;
      padding: 5px 10px;
      border-radius: 8px;
      font-size: 12px;
      z-index: 5;
    }

    .topo2 {
      gap: 20px;
    }
  </style>
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

  <div class="categorias_textao">
    <div class="textao">
      <h2 class="texto1">
        <?php
        if ($categoria > 0 && isset($nomes_categorias[$categoria])) {
          echo $nomes_categorias[$categoria] . " selecionada";
        } else {
          echo "Mais vendidos >";
        }
        ?>
      </h2>

      <p class="texto2">Top jogos mais vendidos da loja</p>
    </div>
    <div class="botaocat">
      <button class="btn-categorias" onclick="toggleCategorias()">
        Categorias Mais Vendidos ▾
      </button>

      <div id="menu-categorias" class="menu-categorias">
        <a href="Index.php">Todos</a>
        <?php foreach ($nomes_categorias as $id => $nome): ?>
          <a href="?categoria=<?php echo $id; ?>"><?php echo $nome; ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>


  <div class="grid-jogos">

    <?php foreach ($jogos as $jogo): ?>

      <a href="../Tela_Jogo/index_jogo.php?id=<?php echo $jogo['id_play']; ?>" style="text-decoration:none; color:white;">

        <div class="card-jogo">

          <div class="badge">
            <?php echo $nomes_categorias[$jogo['id_categoria']] ?? 'Jogo'; ?>
          </div>

          <img src="<?php echo $jogo['Imagens_jogos']; ?>">

          <div class="card-info">
            <h4><?php echo $jogo['titulo']; ?></h4>

            <div class="preco">
              <?php
              if ($jogo['Valor'] > 0) {
                echo "R$ " . number_format($jogo['Valor'], 2, ',', '.');
              } else {
                echo "Gratuito";
              }
              ?>
            </div>
          </div>

        </div>
      </a>

    <?php endforeach; ?>

  </div>

  <script>
    function toggleCategorias() {
      const menu = document.getElementById("menu-categorias");
      menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    }

    document.addEventListener("click", function (e) {
      const dropdown = document.querySelector(".dropdown");
      const menu = document.getElementById("menu-categorias");

      if (!dropdown.contains(e.target)) {
        menu.style.display = "none";
      }
    });

    function toggleMenu() {
      const menu = document.getElementById("user-menu");
      menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    }

    document.addEventListener("click", function (e) {
      const userBox = document.querySelector(".user-box");
      const menu = document.getElementById("user-menu");

      if (userBox && !userBox.contains(e.target)) {
        menu.style.display = "none";
      }
    });

    function toggleMenu() {
      const menu = document.getElementById("user-menu");
      if (menu) {
        menu.style.display = menu.style.display === "flex" ? "none" : "flex";
      }
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