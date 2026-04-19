<?php
session_start();
require_once("../conexa.php");

if (!isset($_SESSION['usuario_nome'])) {
    header("Location: ../Entrar/Entrar.php");
    exit;
}

$email = $_SESSION['usuario_email'];

/* BUSCA USUARIO */
$stmt = $pdo->prepare("SELECT * FROM cadastro WHERE email = :email");
$stmt->bindParam(":email", $email);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$msg = "";

/* 📸 UPLOAD FOTO */
if (isset($_POST['upload_foto'])) {

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . "." . $ext;

        $caminho = "../uploads/" . $nomeArquivo;

        move_uploaded_file($_FILES['foto']['tmp_name'], $caminho);

        $updateFoto = $pdo->prepare("UPDATE cadastro SET url_foto = :foto WHERE email = :email");
        $updateFoto->bindParam(":foto", $caminho);
        $updateFoto->bindParam(":email", $email);
        $updateFoto->execute();

        $usuario['url_foto'] = $caminho;
        $msg = "✅ Foto atualizada!";
    }
}

/* ✏️ ATUALIZAR NOME */
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['upload_foto'])) {

    $novo_user = $_POST['nome_user'];

    if ($novo_user === $usuario['nome_user']) {
        $msg = "⚠️ Nenhuma alteração feita.";
    } else {

        $check = $pdo->prepare("SELECT * FROM cadastro WHERE nome_user = :nome_user AND email != :email");
        $check->bindParam(":nome_user", $novo_user);
        $check->bindParam(":email", $email);
        $check->execute();

        if ($check->rowCount() > 0) {
            $msg = "❌ Esse nome já existe!";
        } else {

            $update = $pdo->prepare("UPDATE cadastro SET nome_user = :nome_user WHERE email = :email");
            $update->bindParam(":nome_user", $novo_user);
            $update->bindParam(":email", $email);
            $update->execute();

            $_SESSION['usuario_nome'] = $novo_user;
            $usuario['nome_user'] = $novo_user;

            $msg = "✅ Nome atualizado!";
        }
    }
}

/* CPF */
function mascararCPF($cpf) {
    return substr($cpf,0,3) . '.***.***-' . substr($cpf,-2);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Conta</title>

<style>

/* RESET */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* BASE */
body {
  height: 100vh;
  overflow: hidden;
  background: #0b1320;
  font-family: 'Segoe UI', sans-serif;
  color: white;
}

body::before {
  content: "";
  position: absolute;
  inset: 0;

  background-image: url('../imagens/logo.png');
  background-size: 180px;
  background-repeat: repeat;

  opacity: 0.04;

  transform: rotate(-15deg) scale(1.2);

  pointer-events: none;
  z-index: -2;
}

/* HEADER */
.topo {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 6%;
  background: rgba(19, 32, 65, 0.95);
  backdrop-filter: blur(10px);
}

.logo {
  width: 100px;
  border-radius: 12px;
}

.topo-esquerda,
.topo-direita {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-nav,
.btn-login {
  border: none;
  color: white;
  padding: 12px 24px;
  border-radius: 25px;
  cursor: pointer;
  font-weight: 600;
  background: rgba(255,255,255,0.05);
  transition: 0.3s;
}

.btn-nav.active,
.btn-login:hover {
  background: #e50914;
}

.btn-icon {
  background: transparent;
  border: none;
  font-size: 28px;
  cursor: pointer;
  color: white;
}

.btn-icon:hover {
  transform: scale(1.3);
}

/* USER */
.user-box {
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(255,255,255,0.05);
  padding: 6px 12px;
  border-radius: 20px;
  cursor: pointer;
  position: relative;
}

.user-img {
  width: 30px;
  height: 30px;
  border-radius: 50%;
}

.user-menu {
  position: absolute;
  top: 45px;
  right: 0;
  background: #13192b;
  border-radius: 10px;
  width: 160px;
  display: none;
  flex-direction: column;
}

.user-menu a {
  padding: 10px;
  color: white;
  text-decoration: none;
  text-align: center;
}

.user-menu a:hover {
  background: #1f2a44;
}

/* CONTEUDO */
.container {
  height: calc(100vh - 80px);
  display: flex;
  justify-content: center;
  align-items: center;
}

/* CARD */
.card-conta {
  display: flex;
  align-items: center;
  gap: 90px;
  background: linear-gradient(160deg, #132041, #0f1a35);
  padding: 70px 90px;
  border-radius: 30px;
  box-shadow: 0 30px 80px rgba(0,0,0,0.7);
  border: 1px solid rgba(255,255,255,0.05);
  min-width: 900px;
}


/* PERFIL */
.perfil {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 25px;
}

.avatar {
  width: 300px;
  height: 300px;
  border-radius: 20px;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.1);
  transition: 0.3s;
}

.avatar:hover {
  transform: scale(1.03);
}

.btn-foto {
  background: rgba(255,255,255,0.08);
  border: none;
  padding: 10px 18px;
  border-radius: 12px;
  color: white;
  cursor: pointer;
  font-size: 13px;
  transition: 0.3s;
}

.btn-foto:hover {
  background: #e50914;
}

.cpf {
  color: #aaa;
  font-size: 13px;
}

/* INFO */
.info {
  display: flex;
  flex-direction: column;
  gap: 18px;
  width: 380px;
}

.info h2 {
  font-size: 30px;
  margin-bottom: 20px;
}

.form-info {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.campo {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.campo label {
  font-size: 13px;
  color: #aaa;
}

input {
  padding: 14px;
  border-radius: 12px;
  border: none;
  background: #1f2a44;
  color: white;
  font-size: 15px;
  transition: 0.2s;
}

input:focus {
  outline: none;
  box-shadow: 0 0 0 2px #e50914;
  background: #263558;
}

button[type="submit"] {
  margin-top: 15px;
  padding: 14px;
  border-radius: 12px;
  border: none;
  background: #e50914;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
  font-size: 15px;
}

button[type="submit"]:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(229,9,20,0.5);
}

.msg {
  margin-top: 10px;
  font-weight: bold;
}

html, body { min-height: 100vh; display: flex; flex-direction: column; margin: 0; }
.container, .main-pagamento, .categoria-container { flex: 1; } /* Empurra o rodapé pro fundo no Pesquisar */

.topo-universal {
    display: flex !important; justify-content: space-between !important; align-items: center !important;
    padding: 15px 5% !important; background: rgba(19, 32, 65, 0.95) !important; width: 100% !important;
    box-sizing: border-box !important; position: relative !important; z-index: 1000 !important;
}
.topo-universal .logo { width: 100px !important; height: auto !important; border-radius: 12px !important; }
.topo-universal .topo-esquerda, .topo-universal .topo-direita { display: flex !important; align-items: center !important; gap: 20px !important; }
.topo-universal .btn-nav, .topo-universal .btn-login {
    border: none !important; color: white !important; padding: 12px 24px !important; border-radius: 25px !important;
    cursor: pointer !important; font-weight: 600 !important; background: rgba(255, 255, 255, 0.05) !important;
    font-size: 15px !important; text-decoration: none !important;
}
.topo-universal .btn-nav.active, .topo-universal .btn-login:hover { background: #e62429 !important; }

/* ÍCONES E USUÁRIO (Para ficar igualzinho à imagem 3) */
.topo-universal .btn-icon { background: transparent !important; border: none !important; font-size: 26px !important; cursor: pointer !important; color: white !important; padding: 0 !important; }
.topo-universal .user-box { display: flex !important; align-items: center !important; gap: 10px !important; background: rgba(255,255,255,0.05) !important; padding: 6px 15px !important; border-radius: 20px !important; cursor: pointer !important; position: relative !important; transition: 0.3s !important; }
.topo-universal .user-box:hover { background: rgba(255,255,255,0.1) !important; }
.topo-universal .user-img { width: 32px !important; height: 32px !important; border-radius: 50% !important; object-fit: cover !important; margin: 0 !important; }
.topo-universal .user-nome { font-size: 15px !important; font-weight: 600 !important; color: white !important; margin: 0 !important; font-family: sans-serif !important;}

/* DROPDOWN MENU */
.topo-universal .user-menu { position: absolute !important; top: 50px !important; right: 0 !important; background: #13192b !important; border-radius: 10px !important; padding: 10px 0 !important; width: 180px !important; display: none; flex-direction: column !important; box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important; z-index: 9999 !important; }
.topo-universal .user-menu a { padding: 12px 20px !important; color: white !important; text-decoration: none !important; font-size: 14px !important; text-align: left !important; display: flex !important; justify-content: space-between !important; align-items: center !important; font-family: sans-serif !important;}
.topo-universal .user-menu a:hover { background: #1f2a44 !important; }

/* BADGES (Bolinhas Vermelhas) */
.badge-bolinha { background: #e62429 !important; color: white !important; border-radius: 50% !important; padding: 2px 6px !important; font-size: 11px !important; font-weight: bold !important; }

/* RODAPÉ BLINDADO */
.rodape-universal { background: #111823 !important; color: #cdd5e0 !important; text-align: center !important; padding: 30px !important; border-top: 1px solid #30363d !important; width: 100% !important; box-sizing: border-box !important; margin-top: auto !important; font-family: sans-serif !important;}


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

<div class="container">
  <div class="card-conta">

    <!-- FOTO -->
    <div class="perfil">
      <form method="POST" enctype="multipart/form-data">
        <img src="<?php echo $usuario['url_foto']; ?>" class="avatar">

        <input type="file" name="foto" id="foto" hidden>
<span class="trocarfoto_salvar">
        <label for="foto" class="btn-foto">Trocar foto</label>
        <button type="submit" name="upload_foto" class="btn-foto">Salvar</button>
      </span>
      </form>

      <div class="cpf">
        CPF: <?php echo mascararCPF($usuario['cpf']); ?>
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

</script>

</body>
</html>

