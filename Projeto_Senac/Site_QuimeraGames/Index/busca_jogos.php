<?php
require_once '../conexa.php';


// CONTAGEM PARA OS BADGES (Cole isso no topo dos seus arquivos PHP)
$qtd_carrinho = 0;
$qtd_wishlist = 0;
if (isset($_SESSION['id_user'])) {
    $stmt_cart = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_cart->execute([$_SESSION['id_user']]);
    $qtd_carrinho = $stmt_cart->fetchColumn();

    $stmt_wish = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_wish->execute([$_SESSION['id_user']]);
    $qtd_wishlist = $stmt_wish->fetchColumn();
}
$logado = isset($_SESSION['usuario_nome']);
$link_home = $logado ? '../Usuario_Logado/usuariologado.php' : '../Index/index.php';


// Pega o que o usuário digitou na barra de pesquisa
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query === '') {
    echo "<p style='color: white;'>Digite algo para pesquisar.</p>";
    exit;
}

try {
    // Busca no banco de dados os jogos que contenham o texto digitado no título
    $stmt = $pdo->prepare("SELECT * FROM jogos WHERE titulo LIKE :busca LIMIT 18");
    $stmt->bindValue(':busca', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Se encontrou algum jogo, monta a grade de cartões
    if (count($resultados) > 0) {
        echo '<div class="jogos-grid">';

        foreach ($resultados as $jogo) {
            $id = $jogo['id_play'];
            $titulo = htmlspecialchars($jogo['titulo']);
            $imagem = htmlspecialchars($jogo['Imagens_jogos']);
            $valor = $jogo['Valor'];

            // Lógica de exibição de preço (Gratuito ou Pago)
            if ($valor > 0) {
                $preco_html = '<span class="v-new">R$ ' . number_format($valor, 2, ',', '.') . '</span>';
            } else {
                $preco_html = '<span class="v-gratis" style="color:#4CAF50; font-weight:bold;">Gratuito</span>';
            }

            // O Cartão do jogo (Igual aos descontos em destaque) apontando para a Tela do Jogo
            echo '
            <a href="../Tela_Jogo/index_jogo.php?id=' . $id . '" style="text-decoration: none; color: inherit; display: block;">
                <div class="card-jogo-container">
                    <div class="thumb-wrapper">
                        <img src="' . $imagem . '" alt="' . $titulo . '">
                    </div>
                    <div class="card-info-texto">
                        <h4>' . $titulo . '</h4>
                        <div class="card-plataforma">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" width="16">
                            <span>Windows</span>
                        </div>
                        <div class="precos-card">
                            ' . $preco_html . '
                        </div>
                    </div>
                </div>
            </a>';
        }
        echo '</div>';
    } else {
        // Se não encontrar nada, mostra uma mensagem amigável
        echo "<h3 style='color: #aaa; text-align: center; margin-top: 60px; font-weight: normal;'>
                Nenhum jogo encontrado para \"<strong style='color:white;'>" . htmlspecialchars($query) . "</strong>\".
              </h3>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'>Erro na busca: " . $e->getMessage() . "</p>";
}
?>