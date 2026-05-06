<?php
session_start();
require_once '../conexa.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'msg' => 'Método inválido']);
    exit;
}

if (!isset($_SESSION['id_user']) || !isset($_SESSION['id_jogo_compra'])) {
    echo json_encode(['sucesso' => false, 'msg' => 'Sessão inválida ou não autenticado']);
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$id_play = (int) $_SESSION['id_jogo_compra'];
$metodo = trim($_POST['metodo'] ?? '');
$usar_coins = isset($_POST['usar_coins']) && $_POST['usar_coins'] === '1';

// Mapeia pagamento
$forma_pgto_map = ['cartao' => 'Crédito', 'pix' => 'Pix', 'boleto' => 'Débito', 'coins' => 'Moedas'];
$forma_pgto = $forma_pgto_map[$metodo] ?? '';

if (empty($forma_pgto)) {
    echo json_encode(['sucesso' => false, 'msg' => 'Método de pagamento inválido']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Busca saldo real do banco
    $stmt_saldo = $pdo->prepare("SELECT coins FROM cadastro WHERE id_user = ?");
    $stmt_saldo->execute([$id_user]);
    $saldo_banco = (int) $stmt_saldo->fetchColumn();

    $preco_original = (float) $_SESSION['preco_compra'];
    $moedas_usadas = 0;
    $valor_pago_real = $preco_original;

    if ($usar_coins && $saldo_banco > 0) {
        $valor_desconto = $saldo_banco * 0.01;

        if ($valor_desconto >= $preco_original) {
            $moedas_usadas = ceil($preco_original / 0.01);
            $valor_pago_real = 0;
        } else {
            $moedas_usadas = $saldo_banco;
            $valor_pago_real = $preco_original - $valor_desconto;
        }
    }

    // 2. REGISTRA A COMPRA NO BANCO
    $stmt_compra = $pdo->prepare("INSERT INTO compra (id_play, id_user, forma_pgto, data_compra) VALUES (?, ?, ?, NOW())");
    $stmt_compra->execute([$id_play, $id_user, $forma_pgto]);
    $id_compra = (int) $pdo->lastInsertId();

    // 3. ENVIA O JOGO PARA A MINHA BIBLIOTECA
    $stmt_bib = $pdo->prepare("INSERT IGNORE INTO minha_biblioteca (id_play, id_user) VALUES (?, ?)");
    $stmt_bib->execute([$id_play, $id_user]);

    // Opcional: Se o jogo estiver no carrinho, limpa ele após a compra
    $stmt_limpa_carrinho = $pdo->prepare("DELETE FROM carrinho WHERE id_usuario = ? AND id_play = ?");
    $stmt_limpa_carrinho->execute([$id_user, $id_play]);

    // 4. ATUALIZAÇÃO FINAL DAS MOEDAS
    $moedas_ganhas = floor($valor_pago_real);
    $novo_saldo = ($saldo_banco - $moedas_usadas) + $moedas_ganhas;

    $stmt_update = $pdo->prepare("UPDATE cadastro SET coins = ? WHERE id_user = ?");
    $stmt_update->execute([$novo_saldo, $id_user]);

    $pdo->commit();
    echo json_encode(['sucesso' => true, 'msg' => 'Compra realizada com sucesso!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>
