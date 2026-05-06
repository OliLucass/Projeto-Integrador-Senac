<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "10.37.44.28"; 
$db = "projeto_quimera";
$usuario = "root";
$senha = "";

try {

    $pdo = new PDO("mysql:host=$host;port=3306;dbname=projeto_quimera;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Conectado com sucesso!"; // só pra teste

} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
    
}
?>