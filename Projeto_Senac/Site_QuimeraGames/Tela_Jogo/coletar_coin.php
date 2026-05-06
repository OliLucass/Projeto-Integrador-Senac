<?php
session_start();
require_once '../conexa.php'; // Ajuste o caminho se necessário

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['sucesso' => false, 'msg' => 'Não logado']);
    exit;
}

$id_user = $_SESSION['id_user'];

try {
    // Adiciona 1 coin ao usuário
    $stmt = $pdo->prepare("UPDATE cadastro SET coins = coins + 1 WHERE id_user = ?");
    $stmt->execute([$id_user]);
    
    echo json_encode(['sucesso' => true]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false]);
}
?>
