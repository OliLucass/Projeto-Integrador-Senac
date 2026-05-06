<?php
session_start();
require_once '../conexa.php';

$logado = isset($_SESSION['usuario_nome']);
$id_user = $_SESSION['id_user'] ?? 0;

// Define para onde a Logo vai mandar (Home logada ou deslogada)
$link_home = $logado ? '../Usuario_Logado/usuariologado.php' : '../Index/index.php';

// Inicializa variáveis para o header não dar erro
$qtd_carrinho = 0;
$qtd_wishlist = 0;
$usuario = ['url_foto' => '', 'coins' => 0];

if ($id_user > 0) {
    // Busca dados do usuário (Foto e Moedas)
    $stmt_u = $pdo->prepare("SELECT url_foto, coins FROM cadastro WHERE id_user = ?");
    $stmt_u->execute([$id_user]);
    $usuario = $stmt_u->fetch(PDO::FETCH_ASSOC);

    // Badges do Header (Bolinhas vermelhas)
    $stmt_c = $pdo->prepare("SELECT COUNT(*) FROM carrinho WHERE id_usuario = ?");
    $stmt_c->execute([$id_user]);
    $qtd_carrinho = $stmt_c->fetchColumn();

    $stmt_w = $pdo->prepare("SELECT COUNT(*) FROM lista_desejos WHERE id_user = ?");
    $stmt_w->execute([$id_user]);
    $qtd_wishlist = $stmt_w->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Quimera Games</title>
    <link rel="stylesheet" href="Sobre.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/global.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="/GitHub/ProjIntegrador/Site_QuimeraGames/favicon.ico">
    <script defer src="Sobre.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>

<body class="sobre-page">

    <header>
        <?php include '../header_footer_global/header.php'; ?>
    </header>

    <main class="sobre-content">

        <!-- HERO -->
        <section class="hero">
            <h1>QUIMERA <span>GAMES</span></h1>

            <p>
                Não somos apenas uma loja. Somos a união de quatro mentes que decidiram criar algo com identidade.
            </p>
        </section>

        <!-- VITRAIS -->
        <section class="vitral-grid">

            <?php
            $equipe = [
                [
                    "leao.png",
                    "LEÃO",
                    "Lucas Oliveira",
                    "24 anos",
                    "Foi onde tudo começou. A base, a estrutura e a visão do projeto nasceram aqui. Responsável por transformar ideias em algo real e funcional.",
                    "https://github.com/OliLucass",
                    "https://www.linkedin.com/in/lc-oliveira/"
                ],

                [
                    "dragao.png",
                    "DRAGÃO",
                    "Guilherme Dias",
                    "18 anos",
                    "Onde existe lógica, existe ele. Responsável por fazer tudo funcionar nos bastidores, conectando sistemas e garantindo que tudo flua como deve.",
                    "https://github.com/Guilherme-odias",
                    "https://www.linkedin.com/in/guilhermedeoliveiradias/"
                ],

                [
                    "bode.png",
                    "BODE",
                    "Cauã Esdras",
                    "18 anos",
                    "A experiência do usuário passa por aqui. Cada detalhe visual, cada interação, tudo foi pensado para ser intuitivo e direto.",
                    "https://github.com/cauaesdras",
                    "https://www.linkedin.com/in/cau%C3%A3-esdras-56066b400/"
                ],

                [
                    "cobra.png",
                    "SERPENTE",
                    "Nicolas Gustavo",
                    "18 anos",
                    "Nada entra sem passar por ele. Segurança, validação e controle são a base do seu trabalho dentro do sistema.",
                    "https://github.com/NicolasM3nezes",
                    "https://www.linkedin.com/in/nicolas-gustavo/"
                ]
            ];

            foreach ($equipe as $m) {
                echo "
            <div class='card-equipe'>
                <div class='card-inner'>

                    <div class='card-front'>
                        <img src='../imagens/$m[0]'>
                        <span>$m[1]</span>
                    </div>

                    <div class='card-back'>
                        <h3>$m[2]</h3>
                        <p class='idade'>$m[3]</p>
                        <p class='desc'>$m[4]</p>

                        <div class='links'>
                            <a href='$m[5]' target='_blank'>GitHub</a>
                            <a href='$m[6]' target='_blank'>LinkedIn</a>
                        </div>
                    </div>

                </div>
            </div>
            ";
            }
            ?>

        </section>

        <!-- HISTORIA -->
        <section class="historia">

            <div class="historia-texto">
                <h2>A Origem da Quimera</h2>

                <p class="destaque">
                    Nem todo projeto nasce com propósito. O nosso nasceu com identidade.
                </p>

                <p>
                    A Quimera Games surgiu da união de quatro desenvolvedores que não queriam entregar apenas mais um
                    sistema.
                    Desde o início, a intenção nunca foi fazer o básico. A inspiração da nossa logo veio da capa de um
                    músico de hiphop, idealizamos uma quimera e a arte do álbum dele foi a mais interessante a partir
                    disso criamos a nossa.
                </p>

                <p>
                    Queríamos algo que tivesse presença. Algo que fosse lembrado.
                </p>

                <p>
                    Inspirados por plataformas como Steam, Epic Games e Nuuvem,
                    seguimos por outro caminho: não copiar — mas reinterpretar.
                </p>

                <p>
                    Cada integrante trouxe uma força diferente. Lógica, criatividade, adaptação e execução.
                </p>

                <p>
                    Separados, funcionam. Juntos, se tornam algo maior.
                </p>

                <p>
                    Essa é a Quimera.
                </p>
            </div>

            <div class="historia-imagem">
                <img src="../imagens/ref.png" alt="Origem Quimera">
            </div>

        </section>

        <!-- MVV -->
        <section class="mvv">

            <div class="mvv-card">
                <h3>Missão</h3>
                <p>
                    Criar experiências que vão além do comum, conectando jogadores com jogos
                    de forma simples, inteligente e até inesperada.
                </p>
            </div>

            <div class="mvv-card">
                <h3>Visão</h3>
                <p>
                    Evoluir constantemente, transformando um projeto acadêmico em algo com potencial real.
                </p>
            </div>

            <div class="mvv-card">
                <h3>Valores</h3>
                <p>
                    Identidade, inovação, trabalho em equipe e a vontade constante de fazer melhor.
                </p>
            </div>

        </section>

    </main>

    <?php include '../header_footer_global/footer.php'; ?>

</body>

</html>