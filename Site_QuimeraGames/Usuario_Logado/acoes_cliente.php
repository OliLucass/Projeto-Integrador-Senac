<?php
session_start();
require_once '../conexa.php';

// Proteção: Garante que o ID do usuário está na sessão
if (!isset($_SESSION['id_user']) && isset($_SESSION['usuario_email'])) {
    $stmt = $pdo->prepare("SELECT id_user FROM cadastro WHERE email = ?");
    $stmt->execute([$_SESSION['usuario_email']]);
    $id = $stmt->fetchColumn();
    if ($id)
        $_SESSION['id_user'] = $id;
}

if (empty($_SESSION['id_user'])) {
    header("Location: ../Entrar/Entrar.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_play = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$acao = $_GET['acao'] ?? '';

// Grava a página exata de onde o usuário clicou
$pagina_anterior = $_SERVER['HTTP_REFERER'] ?? '../Usuario_Logado/usuariologado.php';

if ($id_play > 0) {

    // --- LÓGICA DO CARRINHO ---
    if ($acao == 'add_carrinho') {
        $check = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ? AND id_play = ?");
        $check->execute([$id_user, $id_play]);
        if ($check->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO carrinho (id_usuario, id_play) VALUES (?, ?)")->execute([$id_user, $id_play]);
        }
        header("Location: " . $pagina_anterior);
        exit;
    } elseif ($acao == 'del_carrinho') {
        // Sem o LIMIT 1 (Evita a tela branca)
        $pdo->prepare("DELETE FROM carrinho WHERE id_usuario = ? AND id_play = ?")->execute([$id_user, $id_play]);
        header("Location: " . $pagina_anterior);
        exit;
    }

    // --- LÓGICA DA LISTA DE DESEJOS ---
    elseif ($acao == 'add_wishlist') {
        $check = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ? AND id_play = ?");
        $check->execute([$id_user, $id_play]);

        if ($check->fetchColumn() > 0) {
            $pdo->prepare("DELETE FROM lista_desejos WHERE id_user = ? AND id_play = ?")->execute([$id_user, $id_play]);
        } else {
            $pdo->prepare("INSERT INTO lista_desejos (id_user, id_play) VALUES (?, ?)")->execute([$id_user, $id_play]);
        }
        header("Location: " . $pagina_anterior);
        exit;
    } elseif ($acao == 'del_wishlist') {
        // Sem o LIMIT 1 (Evita a tela branca)
        $pdo->prepare("DELETE FROM lista_desejos WHERE id_user = ? AND id_play = ?")->execute([$id_user, $id_play]);
        header("Location: " . $pagina_anterior);
        exit;
    }
}

// Redirecionamento de segurança final
header("Location: " . $pagina_anterior);
exit;