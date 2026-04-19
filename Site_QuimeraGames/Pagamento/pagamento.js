var pixInterval = null;

document.addEventListener('DOMContentLoaded', function () {

    // Troca de método de pagamento — UM único listener por radio
    var radios = document.querySelectorAll('input[name="metodo"]');
    radios.forEach(function (r) {
        r.addEventListener('change', function (e) {
            var forms = {
                cartao: document.getElementById('dados-cartao'),
                pix: document.getElementById('dados-pix'),
                boleto: document.getElementById('dados-boleto')
            };
            var cards = {
                cartao: document.getElementById('mc-cartao'),
                pix: document.getElementById('mc-pix'),
                boleto: document.getElementById('mc-boleto')
            };

            // remove seleção dos cards
            Object.values(forms).forEach(function (f) { f.classList.remove('ativo'); });
            Object.values(cards).forEach(function (c) { c.classList.remove('sel'); });

            //  marca o card escolhido
            forms[e.target.value].classList.add('ativo');
            cards[e.target.value].classList.add('sel');
        });
    });

    // marca cartão como selecionado
    var mcCartao = document.getElementById('mc-cartao');
    if (mcCartao) mcCartao.classList.add('sel');

    // Máscara do cartão
    var numCartao = document.getElementById('num-cartao');
    if (numCartao) {
        numCartao.addEventListener('input', function () {
            var v = numCartao.value.replace(/\D/g, '').slice(0, 16);
            numCartao.value = v.replace(/(.{4})/g, '$1 ').trim();
        });
    }

    // Máscara de validade
    var valCartao = document.getElementById('val-cartao');
    if (valCartao) {
        valCartao.addEventListener('input', function () {
            var v = valCartao.value.replace(/\D/g, '').slice(0, 4);
            if (v.length > 2) v = v.slice(0, 2) + '/' + v.slice(2);
            valCartao.value = v;
        });
    }

    // Fechar ao clicar fora do modal
    document.querySelectorAll('.overlay').forEach(function (ov) {
        ov.addEventListener('click', function (e) {
            if (e.target === ov) fecharTudo();
        });
    });
});

// ENTRADA PRINCIPAL

function iniciarPagamento() {
    var m = document.querySelector('input[name="metodo"]:checked').value;
    if (m === 'cartao') {
        if (!validarCartao()) return;
        fluxoCartao();
    } else if (m === 'pix') {
        fluxoPix();
    } else {
        fluxoBoleto();
    }
}

// VALIDAÇÃO DO CARTÃO

function showErr(id, show) {
    var el = document.getElementById(id);
    if (el) el.style.display = show ? 'block' : 'none';
}

function validarCartao() {
    var ok = true;

    var num = document.getElementById('num-cartao').value.replace(/\s/g, '');
    if (num.length < 16) {
        showErr('err-num', true);
        document.getElementById('num-cartao').classList.add('input-error');
        ok = false;
    } else {
        showErr('err-num', false);
        document.getElementById('num-cartao').classList.remove('input-error');
    }

    var nome = document.getElementById('nome-cartao').value.trim();
    if (nome.length < 3) {
        showErr('err-nome', true);
        document.getElementById('nome-cartao').classList.add('input-error');
        ok = false;
    } else {
        showErr('err-nome', false);
        document.getElementById('nome-cartao').classList.remove('input-error');
    }

    var val = document.getElementById('val-cartao').value;
    var cvv = document.getElementById('cvv-cartao').value;
    if (val.length < 5 || cvv.length < 3) {
        showErr('err-valcvv', true);
        ok = false;
    } else {
        showErr('err-valcvv', false);
    }

    return ok;
}

// FECHAR OVERLAYS

function fecharTudo() {
    ['ov-cartao', 'ov-pix', 'ov-boleto'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.classList.remove('show');
    });
    if (pixInterval) {
        clearInterval(pixInterval);
        pixInterval = null;
    }
}

function setStep(n) {
    for (var i = 1; i <= 3; i++) {
        var s = document.getElementById('s' + i);
        if (!s) continue;
        s.className = 'step' + (i < n ? ' done' : i === n ? ' active' : '');
    }
    for (var j = 1; j <= 2; j++) {
        var sl = document.getElementById('sl' + j);
        if (!sl) continue;
        sl.className = 'step-line' + (j < n ? ' done' : '');
    }
}

// FLUXO DO CARTÃO

function fluxoCartao() {
    document.getElementById('ov-cartao').classList.add('show');
    setStep(1);
    cartaoFase1();
}

function cartaoFase1() {
    setStep(1);
    document.getElementById('cartao-body').innerHTML =
        '<div class="spinner"></div>' +
        '<h2>Validando dados</h2>' +
        '<p>Verificando as informações do cartão com segurança...</p>' +
        '<div class="prog-wrap"><div class="prog-bar" id="pb1" style="width:0%"></div></div>' +
        '<p class="prog-label" id="pl1">Conectando ao banco...</p>';

    var msgs = [
        'Conectando ao banco...',
        'Validando número do cartão...',
        'Verificando titularidade...',
        'Checando limite disponível...'
    ];
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
    document.getElementById('cartao-body').innerHTML =
        '<div class="spinner"></div>' +
        '<h2>Processando pagamento</h2>' +
        '<p>Aguarde enquanto processamos sua transação com segurança.</p>' +
        '<div class="prog-wrap"><div class="prog-bar" id="pb2" style="width:0%"></div></div>' +
        '<p class="prog-label" id="pl2">Iniciando transação segura...</p>';

    var msgs = [
        'Iniciando transação segura...',
        'Comunicando com a operadora...',
        'Autenticando 3D Secure...',
        'Aguardando aprovação da operadora...',
        'Finalizando...'
    ];
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
    var codigo = '#QG-' + Math.random().toString(36).substr(2, 8).toUpperCase();
    document.getElementById('cartao-body').innerHTML =
        '<div class="check-circle">&#10003;</div>' +
        '<h2 style="color:#22c55e">Pagamento aprovado!</h2>' +
        '<p>Sua compra foi concluída com sucesso. O recibo foi enviado para o seu e-mail cadastrado.</p>' +
        '<div class="badge-success">&#10003; Transação aprovada</div>' +
        '<div class="info-box">' +
        '<p>Código da transação</p>' +
        '<p><strong style="font-family:monospace">' + codigo + '</strong></p>' +
        '</div>' +
        '<button class="btn-modal-primary" onclick="fecharTudo()">Concluir</button>';
}

// FLUXO PIX

function fluxoPix() {
    document.getElementById('ov-pix').classList.add('show');
    document.getElementById('pix-body').innerHTML =
        '<div class="spinner"></div>' +
        '<h2>Gerando QR Code</h2>' +
        '<p>Preparando sua cobrança Pix...</p>';

    setTimeout(function () {
        document.getElementById('pix-body').innerHTML =
            '<h2>Pague com Pix</h2>' +
            '<p>Escaneie o QR Code abaixo com o app do seu banco. O pagamento é confirmado em segundos.</p>' +
            '<div class="qr-container">' +
            '<img src="../Pagamento/Img/qrcode-pix.png" alt="QR Code Pix" width="190" height="190">' +
            '</div>' +
            '<div class="timer" id="pix-timer">04:59</div>' +
            '<p class="timer-label">Tempo restante para pagamento</p>' +
            '<div class="prog-wrap" style="margin:10px 0 18px">' +
            '<div class="prog-bar" id="pix-prog" style="width:100%;background:#22c55e;transition:width 1s linear"></div>' +
            '</div>' +
            '<button class="btn-modal-secondary" onclick="copiarChavePix(this)">&#128203; Copiar chave Pix</button>' +
            '<button class="btn-modal-primary" onclick="simularPagamentoPix()">&#10003; Já fiz o pagamento</button>' +
            '<button class="btn-modal-ghost" onclick="fecharTudo()">Cancelar</button>';

        iniciarTimerPix();
    }, 2000);
}

function iniciarTimerPix() {
    var total = 299;
    pixInterval = setInterval(function () {
        total--;
        var m = Math.floor(total / 60);
        var s = total % 60;
        var el = document.getElementById('pix-timer');
        var pb = document.getElementById('pix-prog');
        if (el) el.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        if (pb) pb.style.width = (total / 299 * 100) + '%';
        if (total <= 0) {
            clearInterval(pixInterval);
            pixInterval = null;
            pixExpirado();
        }
    }, 1000);
}

function pixExpirado() {
    document.getElementById('pix-body').innerHTML =
        '<div class="x-circle">&#10005;</div>' +
        '<h2 style="color:#e62429">QR Code expirado</h2>' +
        '<p>O tempo para pagamento se esgotou. Gere um novo QR Code para tentar novamente.</p>' +
        '<button class="btn-modal-primary" onclick="fluxoPix()">Gerar novo QR Code</button>' +
        '<button class="btn-modal-ghost" onclick="fecharTudo()">Cancelar</button>';
}

function copiarChavePix(btn) {
    var chavePix = 'pagamentos@quimeragames.com.br';
    if (navigator.clipboard) {
        navigator.clipboard.writeText(chavePix).catch(function () { });
    }
    btn.textContent = 'Chave copiada!';
    btn.style.background = '#22c55e';
    btn.style.color = '#fff';
    btn.style.borderColor = '#22c55e';
    setTimeout(function () {
        btn.textContent = 'Copiar chave Pix';
        btn.style.background = '';
        btn.style.color = '#e62429';
        btn.style.borderColor = '#e62429';
    }, 2500);
}

function simularPagamentoPix() {
    if (pixInterval) { clearInterval(pixInterval); pixInterval = null; }
    document.getElementById('pix-body').innerHTML =
        '<div class="spinner"></div>' +
        '<h2>Confirmando pagamento</h2>' +
        '<p>Verificando a transação no sistema Pix...</p>';

    setTimeout(function () {
        var idPix = 'E' + Math.random().toString().substr(2, 23).toUpperCase();
        document.getElementById('pix-body').innerHTML =
            '<div class="check-circle">&#10003;</div>' +
            '<h2 style="color:#22c55e">Pix confirmado!</h2>' +
            '<p>Pagamento recebido e confirmado com sucesso. Sua compra está garantida.</p>' +
            '<div class="badge-success">&#10003; Pix aprovado</div>' +
            '<div class="info-box">' +
            '<p>ID da transação Pix</p>' +
            '<p><strong style="font-family:monospace;font-size:0.78rem">' + idPix + '</strong></p>' +
            '</div>' +
            '<button class="btn-modal-primary" onclick="fecharTudo()">Concluir</button>';
    }, 2500);
}

// FLUXO DO BOLETO

var BOLETO_NUMERO = '3474.07297 25003.671230 01000.038007 4 10010000019990';

function fluxoBoleto() {
    document.getElementById('ov-boleto').classList.add('show');
    document.getElementById('boleto-body').innerHTML =
        '<div class="spinner"></div>' +
        '<h2>Gerando boleto</h2>' +
        '<p>Preparando seu boleto bancário...</p>';

    setTimeout(function () {
        var venc = vencimentoBoleto();
        document.getElementById('boleto-body').innerHTML =
            '<h2>Boleto gerado!</h2>' +
            '<p>Pague até o vencimento em qualquer banco, lotérica ou aplicativo bancário.</p>' +
            '<div class="info-box">' +
            '<p>Vencimento: <strong>' + venc + '</strong></p>' +
            '<p>Valor: <strong>R$ ' + PRECO_JOGO + '</strong></p>' +
            '<p style="margin-top:10px">Linha digitável:</p>' +
            '</div>' +
            '<div class="boleto-code">' + BOLETO_NUMERO + '</div>' +
            '<button class="btn-modal-secondary" onclick="copiarBoleto(this)">&#128203; Copiar código do boleto</button>' +
            '<div class="info-box" style="margin-top:8px;text-align:left">' +
            '<p><strong>Como pagar:</strong></p>' +
            '<p style="margin-top:8px">1. Abra o app do seu banco</p>' +
            '<p>2. Acesse <em>Pagar boleto</em></p>' +
            '<p>3. Cole ou escaneie o código acima</p>' +
            '<p>4. Confirme e aguarde a compensação (até 3 dias úteis)</p>' +
            '</div>' +
            '<button class="btn-modal-ghost" onclick="fecharTudo()">Fechar</button>';
    }, 2000);
}

function vencimentoBoleto() {
    var d = new Date();
    d.setDate(d.getDate() + 3);
    return d.toLocaleDateString('pt-BR');
}

function copiarBoleto(btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(BOLETO_NUMERO).catch(function () { });
    }
    btn.textContent = 'Código copiado!';
    btn.style.background = '#22c55e';
    btn.style.color = '#fff';
    btn.style.borderColor = '#22c55e';
    setTimeout(function () {
        btn.textContent = 'Copiar código do boleto';
        btn.style.background = '';
        btn.style.color = '#e62429';
        btn.style.borderColor = '#e62429';
    }, 2500);
}

function toggleMenu() {
    const menu = document.getElementById("user-menu");
    if (menu) {
        menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    }
}

document.addEventListener("click", function (e) {
    const userBox = document.querySelector(".user-box");
    const menu = document.getElementById("user-menu");
    if (userBox && menu && !userBox.contains(e.target)) {
        menu.style.display = "none";
    }
});
