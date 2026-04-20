<?php
session_start();
require_once '../conexa.php';

if (!isset($_SESSION['id_user'])) exit('Erro: Não logado');

$id_user = $_SESSION['id_user'];
$id_play = (int)$_POST['id_play'];
$nota = (int)$_POST['nota'];

// Verifica se o usuário já avaliou para atualizar ou inserir
$stmt = $pdo->prepare("SELECT id_avaliacao FROM avaliacoes WHERE id_user = ? AND id_play = ?");
$stmt->execute([$id_user, $id_play]);

if ($stmt->fetch()) {
    $sql = "UPDATE avaliacoes SET nota = ? WHERE id_user = ? AND id_play = ?";
    $pdo->prepare($sql)->execute([$nota, $id_user, $id_play]);
} else {
    $sql = "INSERT INTO avaliacoes (id_user, id_play, nota) VALUES (?, ?, ?)";
    $pdo->prepare($sql)->execute([$id_user, $id_play, $nota]);
}
echo "Sucesso";
?>