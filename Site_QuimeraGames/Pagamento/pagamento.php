<?php
session_start();
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


$preco_jogo = "0,00";
$imagem_jogo = "../imagens/logo.png";
$titulo_jogo = "Jogo não encontrado";

if (isset($_GET['id_jogo'], $_GET['preco'])) {
    $id_jogo = (int) $_GET['id_jogo'];
    $preco_raw = (float) $_GET['preco'];
    $preco_jogo = number_format($preco_raw, 2, ',', '.');

    $_SESSION['preco_compra'] = $preco_raw;
    $_SESSION['id_jogo_compra'] = $id_jogo;

    try {
        $stmt = $pdo->prepare("SELECT titulo, Imagens_jogos FROM jogos WHERE id_play = :id");
        $stmt->bindValue(':id', $id_jogo, PDO::PARAM_INT);
        $stmt->execute();
        $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($jogo) {
            $imagem_jogo = $jogo['Imagens_jogos'];
            $titulo_jogo = $jogo['titulo'];
        }
    } catch (PDOException $e) {
        $titulo_jogo = "Erro ao buscar jogo";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - QuimeraGames</title>
    <link rel="stylesheet" href="pagamento.css">
    <script>
        const PRECO_JOGO = "<?php echo $preco_jogo; ?>";
    </script>
</head>

<body>

    <header class="topo-universal">
        <div class="topo-esquerda">
            <a href="<?php echo $link_home; ?>"><img class="logo" src="../imagens/logo.png" alt="Logo"></a>
            <a href="<?php echo $link_home; ?>" style="text-decoration: none;"><button
                    class="btn-nav active">Loja</button></a>
        </div>

        <div class="topo-direita">
            <?php if ($logado): ?>
                <div style="position: relative; display: inline-block;">
                    <button type="button" class="btn-icon"
                        onclick="window.location.href='../Usuario_Logado/carrinho.php'">🛒</button>
                    <?php if (isset($qtd_carrinho) && $qtd_carrinho > 0): ?>
                        <span class="badge-bolinha"
                            style="position: absolute; top: -8px; right: -12px; pointer-events: none;"><?php echo $qtd_carrinho; ?></span>
                    <?php endif; ?>
                </div>

                <div class="user-box" onclick="toggleMenu()">
                    <img src="../imagens/aidento.jpg" class="user-img" alt="Avatar">
                    <span class="user-nome"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>

                    <div id="user-menu" class="user-menu">
                        <a href="../Conta/conta.php">Conta</a>
                        <a href="../Pagamento/pagamento.php">Pagamento</a>
                        <a href="../Usuario_Logado/wishlist.php">
                            Lista de desejo
                            <?php if (isset($qtd_wishlist) && $qtd_wishlist > 0): ?>
                                <span class="badge-bolinha"><?php echo $qtd_wishlist; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="../Usuario_Logado/logout.php">Sair</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../Entrar/Entrar.php" style="text-decoration: none;"><button class="btn-login">Entrar</button></a>
            <?php endif; ?>

            <a href="../Sac/Suporte.php" style="text-decoration: none;"><button class="btn-login">Suporte</button></a>
        </div>
    </header>

    <div class="container">


        <main class="main-pagamento">
            <div class="col-dados">
                <h1>Configurações de pagamento</h1>
                <p class="descricao-pagamento"></p>

                <div class="saldo-container">
                    <span class="label-saldo">Você está comprando</span>
                    <h2 class="valor-saldo"><?php echo htmlspecialchars($titulo_jogo); ?></h2>
                    <span style="font-size:1.4rem; color:#e50914; font-weight:bold;">
                        R$ <?php echo $preco_jogo; ?>
                    </span>
                </div>

                <div id="form-pagamento" class="metodos-pagamento">
                    <h3>Selecione o método de pagamento</h3>

                    <label class="metodo-card" id="mc-cartao">
                        <input type="radio" name="metodo" value="cartao" checked>
                        <span class="icon-placeholder">💳</span>
                        <span>Cartão de crédito</span>
                    </label>

                    <label class="metodo-card" id="mc-pix">
                        <input type="radio" name="metodo" value="pix">
                        <span class="icon-placeholder">💠</span>
                        <span>Pix</span>
                    </label>

                    <label class="metodo-card" id="mc-boleto">
                        <input type="radio" name="metodo" value="boleto">
                        <span class="icon-placeholder">📄</span>
                        <span>Boleto</span>
                    </label>

                    <!-- CARTÃO -->
                    <div id="dados-cartao" class="form-dinamico ativo">
                        <input type="text" placeholder="Número do cartão" class="input-form" id="num-cartao"
                            maxlength="19">
                        <span class="error-msg" id="err-num">Número do cartão inválido</span>
                        <input type="text" placeholder="Nome impresso no cartão" class="input-form" id="nome-cartao">
                        <span class="error-msg" id="err-nome">Informe o nome igual ao cartão</span>
                        <div class="linha-inputs">
                            <input type="text" placeholder="MM/AA" class="input-form" id="val-cartao" maxlength="5">
                            <input type="text" placeholder="CVV" class="input-form" id="cvv-cartao" maxlength="4">
                        </div>
                        <span class="error-msg" id="err-valcvv">Data de validade ou CVV inválido</span>
                    </div>

                    <!-- PIX -->
                    <div id="dados-pix" class="form-dinamico">
                        <p class="texto-info">Clique em <strong>Finalizar Compra</strong> para gerar o QR Code Pix. A
                            aprovação é imediata após o pagamento.</p>
                    </div>

                    <!-- BOLETO -->
                    <div id="dados-boleto" class="form-dinamico">
                        <p class="texto-info">Clique em <strong>Finalizar Compra</strong> para gerar o boleto. Pode
                            levar até 3 dias úteis para compensar.</p>
                    </div>

                    <button type="button" class="btn-finalizar" onclick="iniciarPagamento()">Finalizar Compra</button>
                </div>
            </div>

            <div class="col-logo">
                <img src="<?php echo $imagem_jogo; ?>" alt="Capa do Jogo" class="imagem-jogo">
            </div>
        </main>
    </div>

    <div class="overlay" id="ov-cartao">
        <div class="modal">
            <div class="steps" id="steps-cartao">
                <div class="step active" id="s1">1</div>
                <div class="step-line" id="sl1"></div>
                <div class="step" id="s2">2</div>
                <div class="step-line" id="sl2"></div>
                <div class="step" id="s3">3</div>
            </div>
            <div id="cartao-body"></div>
        </div>
    </div>

    <footer class="rodape">QuimeraGames &copy; 2026</footer>


    <!-- Cartão PIX -->
    <div class="overlay" id="ov-pix">
        <div class="modal">
            <div id="pix-body"></div>
        </div>
    </div>

    <!-- Cartão BOLETO -->
    <div class="overlay" id="ov-boleto">
        <div class="modal">
            <div id="boleto-body"></div>
        </div>
    </div>

    <script src="pagamento.js"></script>
</body>

</html>