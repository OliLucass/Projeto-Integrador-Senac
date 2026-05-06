// ==========================================================
// 1. VARIÁVEIS GLOBAIS E CONTROLE
// ==========================================================
var pixInterval = null;
const PRECO_REAL_JOGO = typeof PRECO_JOGO !== 'undefined' ? PRECO_JOGO : 0;
let saldoCoinsAtual = typeof SALDO_COINS_INICIAL !== 'undefined' ? SALDO_COINS_INICIAL : 0;

function atualizarInterfacePreco() {
    const chk = document.getElementById('chk-usar-coins');
    const querUsarCoins = chk ? chk.checked : false;

    const desconto = querUsarCoins ? (saldoCoinsAtual * 0.01) : 0;
    const precoCalculado = Math.max(0, PRECO_REAL_JOGO - desconto);

    const display = document.getElementById('preco-exibicao');
    if (display) {
        display.innerText = 'R$ ' + precoCalculado.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    const msgGratis = document.getElementById('msg-compra-gratis');
    const formPagto = document.getElementById('form-pagamento');

    if (precoCalculado <= 0 && querUsarCoins) {
        if (msgGratis) msgGratis.style.display = 'block';
        if (formPagto) formPagto.style.display = 'none';
    } else {
        if (msgGratis) msgGratis.style.display = 'none';
        if (formPagto) formPagto.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', () => {

    const chk = document.getElementById('chk-usar-coins');
    if (chk) chk.addEventListener('change', atualizarInterfacePreco);

    const boxCoinsUI = document.getElementById('box-usar-moedas');
    if (boxCoinsUI) {
        boxCoinsUI.addEventListener('click', (e) => {
            if (e.target !== chk && e.target.tagName !== 'LABEL') {
                chk.checked = !chk.checked;
                atualizarInterfacePreco();
            }
        });
    }

    var radios = document.querySelectorAll('input[name="metodo"]');
    radios.forEach(function (r) {
        r.addEventListener('change', function (e) {
            var forms = { cartao: document.getElementById('dados-cartao'), pix: document.getElementById('dados-pix'), boleto: document.getElementById('dados-boleto') };
            var cards = { cartao: document.getElementById('mc-cartao'), pix: document.getElementById('mc-pix'), boleto: document.getElementById('mc-boleto') };
            Object.values(forms).forEach(function (f) { if (f) f.classList.remove('ativo'); });
            Object.values(cards).forEach(function (c) { if (c) c.classList.remove('sel'); });
            if (forms[e.target.value]) forms[e.target.value].classList.add('ativo');
            if (cards[e.target.value]) cards[e.target.value].classList.add('sel');
        });
    });

    document.querySelectorAll('.overlay').forEach(function (ov) {
        ov.addEventListener('click', function (e) {
            if (e.target === ov) fecharTudo();
        });
    });

    // ==========================================================
    // GAMIFICAÇÃO: MASCOTE NEON VERMELHO 
    // ==========================================================
    const spawnDiv = document.getElementById('gamificacao-spawn');
    let imgPath = spawnDiv ? spawnDiv.getAttribute('data-img') : '';

    if (imgPath && imgPath.trim() !== '') {
        setTimeout(() => {
            let mascote = document.createElement('img');
            mascote.src = imgPath;
            mascote.className = 'mascote-gamification';

            // ESTILO PREMIUM: NEON VERMELHO (Cores do site)
            Object.assign(mascote.style, {
                position: 'fixed', width: '100px', height: '100px', borderRadius: '50%',
                zIndex: '2147483647', cursor: 'pointer',
                border: '3px solid #e62429',
                boxShadow: '0 0 20px #e62429, 0 0 40px rgba(229, 9, 20, 0.6)',
                transition: 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                bottom: '40px', objectFit: 'cover'
            });

            if (Math.random() > 0.5) mascote.style.left = '40px';
            else mascote.style.right = '40px';

            mascote.onerror = () => { mascote.remove(); };

            mascote.style.transform = 'scale(0) translateY(50px)';
            document.body.appendChild(mascote);
            setTimeout(() => mascote.style.transform = 'scale(1) translateY(0)', 100);

            let clicado = false;
            mascote.onclick = () => {
                if (clicado) return;
                clicado = true;

                // NOVA ANIMAÇÃO DE CLIQUE: Fica Dourado, Cresce e Voa!
                mascote.style.transition = 'all 0.6s ease';
                mascote.style.transform = 'scale(1.3)';
                mascote.style.borderColor = '#ffd700';
                mascote.style.boxShadow = '0 0 30px #ffd700, 0 0 60px #ffd700';

                setTimeout(() => {
                    mascote.style.transform = 'translateY(-150px) scale(0)';
                    mascote.style.opacity = '0';
                }, 300);

                // FETCH NA TELA DO JOGO
                fetch('../Tela_Jogo/coletar_coin.php', { method: 'POST' })
                    .then(res => res.json())
                    .then(data => {
                        if (data.sucesso) {
                            saldoCoinsAtual++;

                            const counterTopo = document.getElementById('coin-counter');
                            const boxCoins = document.getElementById('box-coins');
                            if (counterTopo) counterTopo.innerText = saldoCoinsAtual;
                            if (boxCoins) {
                                boxCoins.classList.remove('coin-anim');
                                void boxCoins.offsetWidth;
                                boxCoins.classList.add('coin-anim');
                            }

                            const txtCheck = document.getElementById('txt-usar-coins');
                            if (txtCheck) {
                                const desc = (saldoCoinsAtual * 0.01).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                txtCheck.innerText = `Usar minhas ${saldoCoinsAtual} moedas (Desconto de R$ ${desc})`;

                                const boxMoedas = document.getElementById('box-usar-moedas');
                                if (boxMoedas) boxMoedas.style.display = 'block';

                                atualizarInterfacePreco();
                            }
                        }
                    }).catch(err => { /* Erro silencioso */ });

                setTimeout(() => mascote.remove(), 1000);
            };

            setTimeout(() => {
                if (!clicado && mascote.parentElement) {
                    mascote.style.filter = 'blur(10px) grayscale(100%)';
                    mascote.style.opacity = '0';
                    mascote.style.transform = 'translateY(-50px) scale(1.2)';
                    setTimeout(() => mascote.remove(), 1000);
                }
            }, 5000);

        }, 2000);
    }
});

// ==========================================================
// REGISTRO DE COMPRA
// ==========================================================
function registrarCompra(metodo, codigo) {
    var fd = new FormData();
    fd.append('metodo', metodo);
    fd.append('codigo', codigo);

    const chk = document.getElementById('chk-usar-coins');
    const valorUsarCoins = (chk && chk.checked) ? '1' : '0';
    fd.append('usar_coins', valorUsarCoins);

    return fetch('confirmar_compra.php', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .catch(function (e) { return { sucesso: false, msg: e.message }; });
}

function iniciarPagamento() {
    var checked = document.querySelector('input[name="metodo"]:checked');
    if (!checked) return;
    var m = checked.value;
    if (m === 'cartao') { fluxoCartao(); }
    else if (m === 'pix') { fluxoPix(); }
    else { fluxoBoleto(); }
}

// ==========================================================
// MODAIS DE PAGAMENTO COM ANIMAÇÕES RESTAURADAS
// ==========================================================
function fluxoCoins() {
    document.getElementById('ov-cartao').classList.add('show');
    document.getElementById('steps-cartao').style.display = 'none';
    document.getElementById('cartao-body').innerHTML = `
        <div class="spinner"></div>
        <h2>Resgatando jogo...</h2>
        <p>A deduzir as suas moedas e a liberar o jogo na biblioteca.</p>
    `;

    registrarCompra('coins', 'FREE-' + Math.floor(Math.random() * 10000)).then(function (resultado) {
        if (resultado && resultado.sucesso) {
            document.getElementById('cartao-body').innerHTML = `
                <div class="check-circle">&#10003;</div>
                <h2 style="color:#22c55e">Jogo Resgatado!</h2>
                <p>As suas moedas foram aplicadas com sucesso.</p>
                <button class="btn-modal-primary" onclick="window.location.href='../Usuario_Logado/meus_pedidos.php'">Ir para a Biblioteca</button>
            `;
        } else {
            document.getElementById('cartao-body').innerHTML = `
                <div class="x-circle">&#10005;</div>
                <h2 style="color:#e62429">Erro</h2>
                <p>${resultado?.msg || 'Erro desconhecido'}</p>
                <button class="btn-modal-ghost" onclick="fecharTudo()">Fechar</button>
            `;
        }
    });
}

function fluxoCartao() {
    document.getElementById('ov-cartao').classList.add('show'); setStep(1);
    document.getElementById('cartao-body').innerHTML = `
        <div class="spinner"></div>
        <h2>Validando dados</h2>
        <div class="prog-wrap"><div class="prog-bar" id="pb1" style="width:0%"></div></div>
        <p class="prog-label" id="pl1">A conectar ao banco...</p>
    `;

    var msgs = ['A conectar...', 'A validar cartão...', 'A verificar titularidade...', 'A checar limite...'];
    var pct = 0, mi = 0;
    var iv = setInterval(function () {
        pct += 7;
        var pb = document.getElementById('pb1');
        var pl = document.getElementById('pl1');
        if (pb) pb.style.width = Math.min(pct, 100) + '%';
        if (pl && pct % 25 === 0 && mi < msgs.length) pl.textContent = msgs[mi++];
        if (pct >= 100) { clearInterval(iv); setTimeout(cartaoFase2, 400); }
    }, 120);
}

function cartaoFase2() {
    setStep(2);
    document.getElementById('cartao-body').innerHTML = `
        <div class="spinner"></div>
        <h2>A processar pagamento</h2>
        <div class="prog-wrap"><div class="prog-bar" id="pb2" style="width:0%"></div></div>
        <p class="prog-label" id="pl2">A processar...</p>
    `;

    var msgs = ['Iniciando transação...', 'A comunicar operadora...', 'Aguardando aprovação...', 'A finalizar...'];
    var pct = 0, mi = 0;
    var iv = setInterval(function () {
        pct += 4;
        var pb = document.getElementById('pb2');
        var pl = document.getElementById('pl2');
        if (pb) pb.style.width = Math.min(pct, 100) + '%';
        if (pl && pct % 20 === 0 && mi < msgs.length) pl.textContent = msgs[mi++];
        if (pct >= 100) { clearInterval(iv); setTimeout(cartaoFase3, 400); }
    }, 100);
}

function cartaoFase3() {
    setStep(3);
    document.getElementById('cartao-body').innerHTML = '<div class="spinner"></div><h2>A registar compra...</h2>';
    registrarCompra('cartao', '#QG-' + Math.random().toString(36).substr(2, 8).toUpperCase()).then(function (resultado) {
        if (resultado && resultado.sucesso) {
            document.getElementById('cartao-body').innerHTML = `
                <div class="check-circle">&#10003;</div>
                <h2 style="color:#22c55e">Pagamento aprovado!</h2>
                <p>Compra realizada com sucesso e jogo enviado para a biblioteca.</p>
                <button class="btn-modal-primary" onclick="window.location.href='../Usuario_Logado/meus_pedidos.php'">Concluir</button>
            `;
        } else {
            document.getElementById('cartao-body').innerHTML = `
                <div class="x-circle">&#10005;</div>
                <h2 style="color:#e62429">Erro</h2>
                <p>${resultado?.msg || 'Erro'}</p>
                <button class="btn-modal-ghost" onclick="fecharTudo()">Fechar</button>
            `;
        }
    });
}

function fluxoPix() {
    document.getElementById('ov-pix').classList.add('show');
    document.getElementById('pix-body').innerHTML = '<div class="spinner"></div><h2>A gerar QR Code</h2>';
    setTimeout(function () {
        document.getElementById('pix-body').innerHTML = `
            <h2>Pague com Pix</h2>
            <div class="qr-container"><img src="../Pagamento/Img/qrcode-pix.png" width="190" height="190"></div>
            <button class="btn-modal-primary" onclick="simularPagamentoPix()">&#10003; Já fiz o pagamento</button>
            <button class="btn-modal-ghost" onclick="fecharTudo()">Cancelar</button>
        `;
    }, 1500);
}

function simularPagamentoPix() {
    document.getElementById('pix-body').innerHTML = '<div class="spinner"></div><h2>A confirmar...</h2>';
    setTimeout(function () {
        registrarCompra('pix', 'E' + Math.random().toString().substr(2, 23).toUpperCase()).then(function (resultado) {
            if (resultado && resultado.sucesso) {
                document.getElementById('pix-body').innerHTML = `
                    <div class="check-circle">&#10003;</div>
                    <h2 style="color:#22c55e">Pix confirmado!</h2>
                    <p>${resultado.msg}</p>
                    <button class="btn-modal-primary" onclick="window.location.href='../Usuario_Logado/meus_pedidos.php'">Concluir</button>
                `;
            } else {
                document.getElementById('pix-body').innerHTML = `
                    <div class="x-circle">&#10005;</div>
                    <h2 style="color:#e62429">Erro</h2>
                    <p>${resultado?.msg || 'Erro'}</p>
                    <button onclick="fecharTudo()">Fechar</button>
                `;
            }
        });
    }, 2000);
}

function fluxoBoleto() {
    document.getElementById('ov-boleto').classList.add('show');
    document.getElementById('boleto-body').innerHTML = '<div class="spinner"></div><h2>A gerar boleto</h2>';
    registrarCompra('boleto', '3474.07297 25003.671230 01000.038007 4 10010000019990').then(function (resultado) {
        if (resultado && resultado.sucesso) {
            document.getElementById('boleto-body').innerHTML = `
                <h2>Boleto gerado!</h2>
                <p>${resultado.msg}</p>
                <button class="btn-modal-primary" onclick="window.location.href='../Usuario_Logado/meus_pedidos.php'">Concluir</button>
            `;
        } else {
            document.getElementById('boleto-body').innerHTML = `
                <div class="x-circle">&#10005;</div>
                <h2 style="color:#e62429">Erro</h2>
                <p>${resultado?.msg || 'Erro'}</p>
                <button class="btn-modal-ghost" onclick="fecharTudo()">Fechar</button>
            `;
        }
    });
}

function fecharTudo() { ['ov-cartao', 'ov-pix', 'ov-boleto'].forEach(function (id) { var el = document.getElementById(id); if (el) el.classList.remove('show'); }); document.getElementById('steps-cartao').style.display = 'flex'; }
function showErr(id, show) { var el = document.getElementById(id); if (el) el.style.display = show ? 'block' : 'none'; }
function validarCartao() { return true; }
function setStep(n) { for (var i = 1; i <= 3; i++) { var s = document.getElementById('s' + i); if (!s) continue; s.className = 'step' + (i < n ? ' done' : i === n ? ' active' : ''); } }

// ── CONTROLE DO MENU DO USUÁRIO (DROPDOWN) ────────────────
function toggleMenu() {
    const menu = document.getElementById("user-menu");
    if (menu) {
        // Alterna entre flex e none
        menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
    }
}

// Fecha o menu se o usuário clicar em qualquer outro lugar da tela
document.addEventListener("click", function (e) {
    const userBox = document.querySelector(".user-box");
    const menu = document.getElementById("user-menu");
    if (userBox && menu && !userBox.contains(e.target)) {
        menu.style.display = "none";
    }
});
