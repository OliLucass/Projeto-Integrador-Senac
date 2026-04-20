<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifique seu email</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="topo">
    <div class="topo-esquerda">
      <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Index/index.php"><img class="logo"
          src="../imagens/logo.png"></a>
      <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Index/index.php"><button
          class="btn-nav active">Loja</button></a>
    </div>
    <div class="topo-direita">
      <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Entrar/Entrar.php"><button
          class="btn-login">Entrar</button></a>
      <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Sac/Suporte.php"><button
          class="btn-login">Suporte</button></a>
    </div>
  </header>

<?php
session_start();

?>

<form method="POST">
    <div class="container">
        


    <div class="item">

    <div class="parte_cima">
        <h2 class="verif_email">Verifique seu Email</h2>
        <p class="verif_email_texto">Enviamos um código de verificação no email</p>
    </div>

            <div class="butaodumeio">
                <input type="text" name="codigo" id="codigo" class="codigo"><br>

                <button type="submit"  name="verificar"class="verificarr">Verificar</but ton>
            
                <button class="reenviar" type="submit" name="reenviar">Reenviar o código</button>
            </div>
            
        </div>

        <footer class="rodape">
            <button class="btnVoltar" href="">❮</button>
            <label class="labelVoltar"> Voltar</label>
        </footer>

    </div>
</form>
<?php
if(isset($_POST['verificar'])){
    
    $codigoDigitado = $_POST['codigo'];

    if($codigoDigitado == $_SESSION['codigo_verificacao']){

        require_once '../conexa.php';

        $dados = $_SESSION['cadastro'];

        $sql = "INSERT INTO cadastro (email, tipo_user, nome, nome_user, senha, cpf) 
                VALUES (?, 'comum', ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $dados['email'],
            $dados['nome'],
            $dados['user'],
            $dados['senha'],
            $dados['cpf']
        ]);

        // Limpa a seção
        session_destroy();

         header("Location: ../Usuario_Logado/usuariologado.php");
exit;
        
    } 
        else {
        echo "<script>alert('Codigo invalido!');</script>";
        }

        
}
if(isset($_POST['reenviar'])){
    $email = $_SESSION['email_verificacao'] ?? '';
        $codigo = rand(100000, 999999);
        $_SESSION['codigo_verificacao'] = $codigo;

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
            }
            catch (Exception $e) {
            echo "Erro: {$mail->ErrorInfo}";
            }  
    
        }
?>
</body>

</html>