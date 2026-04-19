<?php
// ... (resto do código)

// Use localhost para conexões locais
$host = "192.168.1.87"; 
$db = "projeto_quimera";
$usuario = "root";
$senha = "admin"; // Certifique-se de que a senha do seu root é realmente 'admin'

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conectado com sucesso!"; 
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>