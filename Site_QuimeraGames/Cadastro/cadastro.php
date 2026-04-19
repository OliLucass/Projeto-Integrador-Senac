<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro Quimera</title>

<link rel="stylesheet" href="cStyles.css">
</head>

<body>
    <header class="topo">
    <div class="topo-esquerda">
      <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Index/index.php"><img class="logo"
          src="../imagens/logo.png"></a>
    </div>
    <div class="topo-direita">
      <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Sac/Suporte.php"><button
          class="btn-login">Suporte</button></a>
    </div>
</header>

<div class="h1box">
<div class="conteudo">

    <form class="card" method="POST" onsubmit="return validarForm()">
    <?$nome_padrao = "comum"; ?>
        <h1>Tela Cadastro</h1>

        <div class="input-group">
            <input type="email" placeholder=" " id="email" name="email" required>
            <label>Email</label>
        </div>
        
        <div class="input-group">
            <input type="hidden" id="comum" name="comum" value="<?php echo $nome_padrao; ?>">
            
        </div>

        <div class="input-group">
            <input type="text" placeholder=" " id="nome" name="nome" required>
            <label>Nome</label>
        </div>

        <div class="input-group">
            <input type="text" placeholder=" " id="user" name="user" required>
            <label>User</label>
        </div>

        <div class="input-group">
            <input type="text" placeholder=" " id="cpf" name="cpf" maxlength="14" required>
            <label>CPF</label>
        </div>

        <div class="input-group">
            <input type="password" placeholder=" " id="senha" name="senha" required>
            <label>Senha</label>
        </div>

        <div class="input-group">
            <input type="password" placeholder=" "id="confirme" name="confirme" required>
            <label>Confirmar senha</label>
        </div>


        <div class="check">
            <input type="checkbox" onclick="mostrarSenha()"> Mostrar senha
        </div>

        <button class="btn" id="btn" name="acao"  onclick="this.disabled=true; value="cadastrar">Cadastrar</button>

        <div class="footer">
            <button type="button" class="voltar" onclick="window.location.href='../Index/index.php'">←</button>
            <span>Voltar</span>
        </div>

    </form>
<div class="rodape">
        <div class="primeira_cadastre">
    <label class="primeira" >Já tem uma conta?
        Faça login para continuar.</label>
    <button class="cad" onclick="window.location.href='../Entrar/entrar.php'">Entrar</button>
    </div>

    <div class="texto">
    <p>É gratuito e fácil. Descubra milhares de jogos
    para jogar com milhões de novos amigos.</p>
    </div>
</div>

</div>
</div>
<?php 
require_once '../conexa.php';


$tipo_comum = "comum";
if($_POST) {


$email = $_POST['email'] ?? '';
$comum = $_POST['comum'] ?? $tipo_comum;
$nome  = $_POST['nome'] ?? '';
$user  = $_POST['user'] ?? '';
$cpf   = $_POST['cpf'] ?? '';
$senha = $_POST['senha'] ?? '';


// Validação Email
$stmt = $pdo->prepare("SELECT COUNT(*) FROM cadastro WHERE email = ?");
$stmt->execute([$email]);
if($stmt->fetchColumn() > 0){
    echo "<script>alert('Email já cadastrado!');</script>";
    exit;
}
// Validação User
$stmt = $pdo->prepare("SELECT COUNT(*) FROM cadastro WHERE nome_user = ?");
$stmt->execute([$user]);
if($stmt->fetchColumn() > 0){
    echo "<script>alert('Usuário já existe!');</script>";
    exit;
}
// Validação CPF
$stmt = $pdo->prepare("SELECT COUNT(*) FROM cadastro WHERE cpf = ?");
$stmt->execute([$cpf]);
if($stmt->fetchColumn() > 0){
    echo "<script>alert('CPF já cadastrado!');</script>";
    exit;
}
else {
    session_start();

// Gerar o codigo
$codigo = rand(100000, 999999);

// Salvar os dados em uma seção
$_SESSION['cadastro'] = [
    'email' => $email,
    'comum' => $comum,
    'nome' => $nome,
    'user' => $user,
    'senha' => $senha, 
    'cpf' => $cpf
    
];

$_SESSION['codigo_verificacao'] = $codigo;
$_SESSION['email_verificacao'] = $email;


require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'quimeraggames@gmail.com';
    $mail->Password = 'okvj nqpq jgqk cexh';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('quimeraggames@gmail.com', 'Quimera');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Verification code';
    $mail->Body = "
        <h2>Bem-vindo ao Quimera 🚀</h2>
        <p>Seu código de verificação é:</p>
        <h1 style='color:red;'>$codigo</h1>
    ";

    $mail->send();

    header("Location: ../Verificar_email/index.php");
exit;

} catch (Exception $e) {
    echo "Erro: {$mail->ErrorInfo}";
}
}
}

?>

<script src="script.js"></script>

</body>
</html>