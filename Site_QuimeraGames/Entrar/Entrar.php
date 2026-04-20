<?php
session_start();
require_once("../conexa.php");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$erro = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("
        SELECT * FROM cadastro 
        WHERE (email = :usuario OR nome_user = :usuario)
    ");

    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();

    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados && $senha == $dados["senha"]) {
        $_SESSION['usuario_nome'] = $dados['nome_user'];
        $_SESSION['usuario_email'] = $dados['email'];

        // Alterado para 'id_user' que é o nome real na sua tabela cadastro
        $_SESSION['id_user'] = $dados['id_user'];

        header("Location: ../Usuario_logado/usuariologado.php");
        exit;

    } else {
        header("Location: Entrar.php?erro=1"); // 🔥 REDIRECIONA
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Entrar</title>
    <link rel="stylesheet" href="styless.css">

    <style>
        .conteudo {
            position: relative;
        }

        .erro-msg {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);

            background-color: red;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 999;
            transition: 0.5s;
        }
    </style>
</head>

<body>

    <header class="topo">
        <div class="topo-esquerda">
            <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Index/index.php"><img class="logo"
                src="../imagens/logo.png"></a>
        </div>
    </header>

    <div class="tela">
        <div class="h1box">
            <h1>Entre na Quimera Games</h1>

            <div class="conteudo">

                <!-- ERRO -->
                <?php if (isset($_GET['erro'])): ?>
                    <div id="erro-msg" class="erro-msg">
                        Usuário ou senha incorretos!
                    </div>
                <?php endif; ?>

                <script>
                    if (window.location.search.includes("erro")) {
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                </script>

                <form method="post" action="Entrar.php">

                    <div class="div1">
                        <label>Nome de usuário ou email:</label>
                        <input name="usuario" required>
                    </div>

                    <div class="div2">
                        <label>Senha:</label>
                        <input type="password" name="senha" id="senha" required>


                        <div class="lembrar">
                            <input type="checkbox" id="lembrar">
                            <label for="lembrar">👀</label>
                        </div>
                    </div>

                    <script>
                        document.getElementById("lembrar").addEventListener("change", function () {
                            const senha = document.getElementById("senha");

                            if (this.checked) {
                                senha.type = "text";
                            } else {
                                senha.type = "password";
                            }
                        });
                    </script>

                    <button type="submit" class="iniciar_sessao">Iniciar Sessão</button>

                </form>

                <a href="http://localhost/GitHub/ProjIntegrador/Site_QuimeraGames/Sac/Suporte.php"
                    class="problemas_iniciar">Problemas para iniciar sessão</a>

            </div>
        </div>
    </div>

    <div class="tudoai">
        <div class="primeira_cadastre">
            <label class="primeira">Primeira vez na Quimera?</label>
            <button class="cad" onclick="window.location.href='../Cadastro/cadastro.php'">Cadastre-se</button>
        </div>

        <div class="texto">
            <p>É gratuito e fácil. Descubra milhares de jogos
                para jogar com milhões de novos amigos.</p>
        </div>
    </div>

    <!-- 🔥 JS QUE FAZ SUMIR -->
    <script>
        setTimeout(() => {
            const erro = document.getElementById("erro-msg");
            if (erro) {
                erro.style.opacity = "0";
                setTimeout(() => {
                    erro.remove();
                }, 500);
            }
        }, 5000);
    </script>

</body>

</html>