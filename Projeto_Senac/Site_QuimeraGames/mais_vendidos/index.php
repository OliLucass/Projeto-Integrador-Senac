<?php
session_start();
require_once("../conexa.php");

// 1. Definições básicas de usuário
$logado = isset($_SESSION['usuario_nome']);
$id_user = $_SESSION['id_user'] ?? 0;
$email_user = $_SESSION['usuario_email'] ?? '';

// 2. Inicializa variáveis para o HEADER
$qtd_carrinho = 0;
$qtd_wishlist = 0;
$usuario = ['url_foto' => '']; // Valor inicial vazio para a foto

if ($logado && $id_user > 0) {
  // BUSCA FOTO DO USUÁRIO (O que faltava aqui)
  $stmt_u = $pdo->prepare("SELECT url_foto FROM cadastro WHERE email = ?");
  $stmt_u->execute([$email_user]);
  $usuario = $stmt_u->fetch(PDO::FETCH_ASSOC) ?: $usuario;

  // CONTAGEM PARA BADGES
  $stmt_cart = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
  $stmt_cart->execute([$id_user]);
  $qtd_carrinho = $stmt_cart->fetchColumn();

  $stmt_wish = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
  $stmt_wish->execute([$id_user]);
  $qtd_wishlist = $stmt_wish->fetchColumn();
}

$link_home = $logado ? '../Usuario_Logado/usuariologado.php' : '../Index/index.php';

// 3. Lógica das Categorias
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
  // ... (Sua lógica try/catch de consulta dos jogos permanece exatamente igual)
  if ($categoria > 0) {
    $stmt = $pdo->prepare("SELECT j.*, COUNT(c.id_play) as total_vendas FROM jogos j LEFT JOIN compra c ON j.id_play = c.id_play WHERE j.id_categoria = :categoria GROUP BY j.id_play ORDER BY total_vendas DESC LIMIT 12");
    $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);
  } else {
    $stmt = $pdo->prepare("SELECT j.*, COUNT(c.id_play) as total_vendas FROM jogos j LEFT JOIN compra c ON j.id_play = c.id_play GROUP BY j.id_play ORDER BY total_vendas DESC LIMIT 20");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mais Vendidos - QuimeraGames</title>
  <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
  <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="stylee.css?v=<?php echo time(); ?>">
</head>

<body>

  <header class="topo-universal">
    <?php include '../header_footer_global/header.php'; ?>
  </header>

  <div class="categorias_textao">
    <div class="textao">
      <h2 class="texto1">
        <?php
        if ($categoria > 0 && isset($nomes_categorias[$categoria])) {
          echo $nomes_categorias[$categoria] . "";
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
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" alt="Steam">
            <?php echo $nomes_categorias[$jogo['id_categoria']] ?? 'Jogo'; ?>
          </div>

          <img src="<?php echo $jogo['Imagens_jogos']; ?>" alt="<?php echo $jogo['titulo']; ?>">

          <div class="card-info">
            <h4><?php echo $jogo['titulo']; ?></h4>
            <div class="preco">
              <?php echo ($jogo['Valor'] > 0) ? "R$ " . number_format($jogo['Valor'], 2, ',', '.') : "Gratuito"; ?>
            </div>
          </div>

        </div>
      </a>
    <?php endforeach; ?>
  </div>
  </div>

  <script>
    function toggleCategorias() {
      const menu = document.getElementById("menu-categorias");
      // Agora trocamos pela classe .show que tem a animação
      menu.classList.toggle("show");
    }

    // Atualize o fechamento ao clicar fora também:
    document.addEventListener("click", function (e) {
      const btnCategorias = document.querySelector(".botaocat");
      const menuCategorias = document.getElementById("menu-categorias");
      if (btnCategorias && menuCategorias && !btnCategorias.contains(e.target)) {
        menuCategorias.classList.remove("show"); // Remove a classe show
      }

      // Para o menu do usuário
      const userBox = document.querySelector(".user-box");
      const menuUser = document.getElementById("user-menu");
      if (userBox && menuUser && !userBox.contains(e.target)) {
        menuUser.style.display = "none";
      }
    });

    // Função única do menu do usuário
    function toggleMenu() {
      const menu = document.getElementById("user-menu");
      if (menu) {
        menu.style.display = menu.style.display === "flex" ? "none" : "flex";
      }
    }
  </script>

  <?php include '../header_footer_global/footer.php'; ?>
</body>

</html>
