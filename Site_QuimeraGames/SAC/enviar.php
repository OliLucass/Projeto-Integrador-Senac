<?php
ob_start();

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

header('Content-Type: application/json');

try {
    require_once '../PHPMailer/src/PHPMailer.php';
    require_once '../PHPMailer/src/SMTP.php';
    require_once '../PHPMailer/src/Exception.php';
    require_once '../conexa.php';
} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao carregar dependências: ' . $e->getMessage()]);
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ===== CONFIGURAÇÕES — edite só aqui =====
$SMTP_USUARIO   = 'quimeraggames@gmail.com';
$SMTP_SENHA     = 'okvjnqpqjgqkcexh';
$EMAIL_COPIA    = 'quimeraggames@gmail.com';
$BASE_URL       = 'http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Suporte';
$ZEROBOUNCE_KEY = '82781732b188490e9ae27a327541db8d'; // https://zerobounce.net (100 gratis/mes)
// =========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado']);
    exit;
}

function validarEmailCompleto($email, $apiKey) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Formato de e-mail invalido.";
    }

    $url = 'https://api.zerobounce.net/v2/validate?' . http_build_query([
        'api_key'    => $apiKey,
        'email'      => $email,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $ctx      = stream_context_create(['http' => ['timeout' => 8]]);
    $resposta = @file_get_contents($url, false, $ctx);

    if ($resposta === false) {
        return true; // API nao respondeu, nao bloqueia
    }

    $dados = json_decode($resposta, true);

    if (!$dados || !isset($dados['status'])) {
        return true;
    }

    $status    = strtolower($dados['status']);
    $subStatus = strtolower($dados['sub_status'] ?? '');

    switch ($status) {
        case 'valid':
            return true;
        case 'invalid':
            return "E-mail invalido ou inexistente.";
        case 'spamtrap':
        case 'abuse':
            return "E-mail nao permitido.";
        case 'do_not_mail':
            if ($subStatus === 'disposable') {
                return "E-mails temporarios/descartaveis nao sao permitidos.";
            }
            return "Este e-mail nao pode ser usado para contato.";
        case 'catch-all':
        case 'unknown':
        default:
            return true;
    }
}

$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cpf   = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$msg   = trim($_POST['reclamacao'] ?? '');

if (strlen($nome) < 3) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Nome invalido']); exit;
}

$validacaoEmail = validarEmailCompleto($email, $ZEROBOUNCE_KEY);
if ($validacaoEmail !== true) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => $validacaoEmail]); exit;
}

if (strlen($cpf) != 11) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'CPF invalido']); exit;
}

if (strlen($msg) < 20) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Mensagem muito curta']); exit;
}

$token     = bin2hex(random_bytes(32));
$protocolo = strtoupper(substr(bin2hex(random_bytes(8)), 0, 8));

try {
    $stmt = $pdo->prepare("
        INSERT INTO suporte 
        (nome, email, cpf, mensagem, protocolo, token, confirmado, data_envio)
        VALUES (:nome, :email, :cpf, :msg, :protocolo, :token, 0, NOW())
    ");
    $stmt->execute([
        ':nome'      => $nome,
        ':email'     => $email,
        ':cpf'       => $cpf,
        ':msg'       => $msg,
        ':protocolo' => $protocolo,
        ':token'     => $token
    ]);
} catch (PDOException $e) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro banco: ' . $e->getMessage()]);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'quimeraggames@gmail.com';
    $mail->Password   = 'cyrsiodgwwzvwuqx';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    $mail->Timeout    = 15;

    $mail->setFrom($SMTP_USUARIO, 'QuimeraGames');
    $mail->addAddress($email, $nome);
    $mail->addCC($EMAIL_COPIA, 'Suporte QuimeraGames');

    $mail->isHTML(true);
    $mail->Subject = "Recebemos sua solicitacao - Protocolo: $protocolo";

    $nomeSeguro      = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    $textoReclamacao = nl2br(htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'));
    $link            = $BASE_URL . "/confirmar.php?token=$token";

    $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width:600px; color:#222;'>
        <h2 style='color:#1b3c91;'>Confirmacao de E-mail - QuimeraGames</h2>
        <p>Ola <strong>$nomeSeguro</strong>,</p>
        <p>Recebemos sua solicitacao. Para confirmar que este e-mail e valido, clique no botao abaixo:</p>
        <p>
            <a href='$link'
               style='display:inline-block;padding:12px 24px;background:#1b3c91;
                      color:#fff;border-radius:6px;text-decoration:none;font-size:15px;'>
                Confirmar meu e-mail
            </a>
        </p>
        <hr style='border:none;border-top:1px solid #ddd;margin:20px 0;'>
        <p><strong>Protocolo:</strong> $protocolo</p>
        <p><strong>Mensagem enviada:</strong></p>
        <blockquote style='background:#f4f4f4;padding:10px 14px;border-left:4px solid #1b3c91;border-radius:4px;'>
            $textoReclamacao
        </blockquote>
        <p style='font-size:12px;color:#888;margin-top:20px;'>
            Se voce nao fez essa solicitacao, ignore este e-mail.
        </p>
    </div>
    ";

    $mail->AltBody = "Ola $nome,\n\nRecebemos sua solicitacao.\n\nProtocolo: $protocolo\n\nPara confirmar seu e-mail acesse:\n$link\n\nMensagem enviada:\n$msg\n\nSe nao fez essa solicitacao, ignore este e-mail.";

    $mail->send();

    ob_end_clean();
    echo json_encode(['sucesso' => true, 'protocolo' => $protocolo]);

} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro SMTP: ' . $mail->ErrorInfo]);
} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro inesperado: ' . $e->getMessage()]);
}