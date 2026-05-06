<?php
// Configuração do banco de dados
$host = "192.168.1.87"; // ou "localhost" se for local
$db = "projeto_quimera";
$usuario = "root";
$senha = "admin"; // verifique se essa senha está correta

try {
    // Cria conexão PDO
    $pdo = new PDO(
        "mysql:host=$host;port=3306;dbname=$db;charset=utf8",
        $usuario,
        $senha
    );

    // Define modo de erro como exceção (boa prática)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // conexão OK (não exibe mensagem no site)

} catch (PDOException $e) {
    // Mostra erro apenas se falhar conexão
die("ESTE ARQUIVO ESTÁ SENDO EXECUTADO");
}
?>