<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte ao Cliente - QuimeraGames</title>
    <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Suporte.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
</head>

<body>

    <?php include '../header_footer_global/header_simples.php'; ?>

    <main class="main-suporte">
        <div class="card-suporte">
            <h1>Suporte ao Cliente</h1>

            <div id="blocoErro" class="bloco-erro" style="display:none;">
                <ul id="listaErros"></ul>
            </div>

            <form id="formSuporte" action="enviar.php" method="POST">

                <div class="input-group">
                    <input type="text" name="nome" id="name" required placeholder=" " autocomplete="off">
                    <label>Nome</label>
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" required placeholder=" " autocomplete="off">
                    <label>Email</label>
                </div>

                <div class="input-group">
                    <input type="text" name="cpf" id="cpf" maxlength="14" required placeholder=" " autocomplete="off">
                    <label>CPF</label>
                </div>

                <div class="input-group textarea-group">
                    <textarea name="reclamacao" id="reclamacao" required placeholder=" "></textarea>
                    <label>Escreva sua reclamação/sugestão aqui...</label>
                    <button type="submit" id="bntContinuar" class="btnEnviar">➤</button>
                </div>

                <div class="footer-voltar">
                    <button type="button" class="btnVoltar" onclick="window.history.back()">❮</button>
                    <span class="labelVoltar" onclick="window.history.back()">Voltar</span>
                </div>

            </form>
        </div>
    </main>

    <?php include '../header_footer_global/footer.php'; ?>

    <div id="modalSucesso" class="modal-overlay">
        <div class="modal-content">
            <div class="icon-check">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2>Solicitação Enviada</h2>
            <p>Seu protocolo de atendimento é: <br><strong id="numeroProtocolo"></strong></p>
            <button id="btnFecharModal">Entendi</button>
        </div>
    </div>

    <script src="../js/Suporte.js" defer></script>
</body>

</html>
