<?php
session_start();
require_once("../conexa.php");

// Proteção de acesso
if (!isset($_SESSION['usuario_nome'])) {
  header("Location: ../entrar/entrar.php");
  exit;
}

$email = $_SESSION['usuario_email'];
$id_user = $_SESSION['id_user'] ?? 0;
$logado = true;
$link_home = '../usuario_logado/usuariologado.php';

// --- INÍCIO DA LÓGICA DE DADOS (Unificada) ---

/* 1. BUSCA USUARIO E BADGES */
// Buscamos tudo de uma vez para garantir que as variáveis existam
$stmt = $pdo->prepare("SELECT * FROM cadastro WHERE email = :email");
$stmt->bindParam(":email", $email);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$qtd_carrinho = 0;
$qtd_wishlist = 0;

if ($id_user > 0) {
    $stmt_c = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_c->execute([$id_user]);
    $qtd_carrinho = $stmt_c->fetchColumn();

    $stmt_w = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_w->execute([$id_user]);
    $qtd_wishlist = $stmt_w->fetchColumn();
}

$msg = "";

/* 📸 UPLOAD FOTO */
if (isset($_POST['upload_foto'])) {
  if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = uniqid() . "." . $ext;
    $caminhoFisico = "../uploads/" . $nomeArquivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFisico)) {

        /* APAGA FOTO ANTIGA */
        if (!empty($usuario['url_foto'])) {
            $fotoAntiga = "../uploads/" . $usuario['url_foto'];

            if (file_exists($fotoAntiga)) {
                unlink($fotoAntiga);
            }
        }

        /* SALVA NOVA FOTO NO BANCO */
        $updateFoto = $pdo->prepare("
            UPDATE cadastro 
            SET url_foto = :foto 
            WHERE email = :email
        ");

        $updateFoto->execute([
            ':foto' => $nomeArquivo,
            ':email' => $email
        ]);

        $usuario['url_foto'] = $nomeArquivo;
        $msg = "✅ Foto atualizada!";
    }
  }
}

/* ✏️ ATUALIZAR NOME */
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['upload_foto'])) {
  $novo_user = $_POST['nome_user'];
  if ($novo_user === $usuario['nome_user']) {
    $msg = "⚠️ Nenhuma alteração feita.";
  } else {
    $check = $pdo->prepare("SELECT id_user FROM cadastro WHERE nome_user = :nome_user AND email != :email");
    $check->execute([':nome_user' => $novo_user, ':email' => $email]);
    if ($check->rowCount() > 0) {
      $msg = "❌ Esse nome já existe!";
    } else {
      $update = $pdo->prepare("UPDATE cadastro SET nome_user = :nome_user WHERE email = :email");
      $update->execute([':nome_user' => $novo_user, ':email' => $email]);
      $_SESSION['usuario_nome'] = $novo_user;
      $usuario['nome_user'] = $novo_user;
      $msg = "✅ Nome atualizado!";
    }
  }
}

function mascararCPF($cpf) {
  return substr($cpf, 0, 3) . '.***.***-' . substr($cpf, -2);
}
// --- FIM DA LÓGICA ---
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conta</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
  <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
</head>

<body>

  <header>
    <?php include '../header_footer_global/header.php'; ?>
  </header>

  <div class="container">
    <div class="card-conta">

      <!-- FOTO -->
      <div class="perfil">

        <div class="perfil-esquerda">
          <form id="formFoto" method="POST" enctype="multipart/form-data">
            <img src="../uploads/<?php echo $usuario['url_foto']; ?>" class="avatar">
            <input type="file" name="foto" id="foto" hidden>
          </form>

          <div class="cpf">
            CPF: <?php echo mascararCPF($usuario['cpf']); ?>
          </div>
        </div>

        <div class="trocarfoto_salvar">
          <label for="foto" class="btn-foto">Trocar foto</label>
          <button type="submit" form="formFoto" name="upload_foto" class="btn-foto">Salvar</button>
        </div>

      </div>
      <!-- INFO -->
      <div class="info">
        <h2>Configurações da Conta</h2>

        <form method="POST" class="form-info">

          <div class="campo">
            <label>Apelido de usuário</label>
            <input name="nome_user" value="<?php echo $usuario['nome_user']; ?>">
          </div>

          <div class="campo">
            <label>Nome completo</label>
            <input value="<?php echo $usuario['nome']; ?>" readonly>
          </div>

          <div class="campo">
            <label>Email</label>
            <input value="<?php echo $usuario['email']; ?>" readonly>
          </div>

          <button type="submit">Atualizar dados</button>
        </form>

        <?php if ($msg): ?>
          <p class="msg"><?php echo $msg; ?></p>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <script>
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
  <?php include '../header_footer_global/footer.php'; ?>

</body>

</html>
