<?php
require_once '../conexa.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die("<h2>Token inválido</h2>");
}

// buscar registro
$stmt = $pdo->prepare("SELECT * FROM suporte WHERE token = ?");
$stmt->execute([$token]);

if ($stmt->rowCount() == 0) {
    die("<h2>Token inválido ou expirado</h2>");
}

// atualizar confirmação
$pdo->prepare("UPDATE suporte SET confirmado = 1 WHERE token = ?")
    ->execute([$token]);

echo "
<h2 style='color:green;'>E-mail confirmado com sucesso!</h2>
<p>Sua solicitação foi validada e será analisada.</p>
";