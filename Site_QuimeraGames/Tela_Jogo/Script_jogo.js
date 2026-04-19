document.addEventListener('DOMContentLoaded', () => {

    // =======================================================
    // 1. VERIFICAÇÃO DE LOGIN E AÇÕES (CARRINHO / WISHLIST)
    // =======================================================
    const infoSessao = document.getElementById('dados-sessao');
    const logado = infoSessao ? infoSessao.getAttribute('data-logado') === 'true' : false;
    const idJogo = infoSessao ? infoSessao.getAttribute('data-jogo') : 0;

    const gerenciarAcao = (acao) => {
        if (!logado) {
            window.location.href = '../Entrar/Entrar.php';
        } else {
            window.location.href = `../Usuario_Logado/acoes_cliente.php?id=${idJogo}&acao=${acao}`;
        }
    };

    // Listeners dos botões de ação
    document.getElementById('btn-add-carrinho')?.addEventListener('click', () => gerenciarAcao('add_carrinho'));
    document.getElementById('btn-add-wishlist')?.addEventListener('click', () => gerenciarAcao('add_wishlist'));
    document.getElementById('btn-comprar-agora')?.addEventListener('click', () => {
    if (!logado) {
        window.location.href = '../Entrar/Entrar.php';
        return;
    }
    const precoEl = document.getElementById('preco-final');
    const preco = precoEl ? precoEl.getAttribute('data-valor') : '0';
    window.location.href = `../Pagamento/pagamento.php?id_jogo=${idJogo}&preco=${preco}`;
});


    // =======================================================
    // 2. LÓGICA DA GALERIA (SWAP DE MÍDIA - ORIGINAL)
    // =======================================================
    const galeriaThumbnails = document.getElementById('galeria-thumbnails');
    const painelMidia = document.getElementById('painel-midia');
    const videoContainer = document.getElementById('media-video-container');
    const imageContainer = document.getElementById('media-image-container');
    const iframeVideo = document.getElementById('video-iframe');
    const mainImage = document.getElementById('main-image');

    if (galeriaThumbnails && painelMidia) {
        galeriaThumbnails.addEventListener('click', function (e) {
            const clickedWrapper = e.target.closest('.thumb-wrapper');
            if (!clickedWrapper) return;

            const clickedType = clickedWrapper.dataset.type;
            const clickedSrc = clickedWrapper.dataset.src;
            const mainType = painelMidia.dataset.type;
            const mainSrc = painelMidia.dataset.src;

            painelMidia.dataset.type = clickedType;
            painelMidia.dataset.src = clickedSrc;
            clickedWrapper.dataset.type = mainType;
            clickedWrapper.dataset.src = mainSrc;

            if (clickedType === 'video') {
                videoContainer.style.display = 'block';
                imageContainer.style.display = 'none';
                iframeVideo.src = clickedSrc;
            } else {
                videoContainer.style.display = 'none';
                imageContainer.style.display = 'block';
                mainImage.src = clickedSrc;
                if (iframeVideo) iframeVideo.src = iframeVideo.src;
            }

            if (mainType === 'video') {
                clickedWrapper.innerHTML = `
                    <div class="thumb-video-btn" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#222; color:#fff; border-radius:4px;">
                        <span>▶ Trailer</span>
                    </div>`;
            } else {
                clickedWrapper.innerHTML = `<img src="${mainSrc}" class="thumb-item" style="width:100%; height:100%; object-fit:cover; display:block;">`;
            }
        });
    }

    // =======================================================
    // 3. SISTEMA DE AVALIAÇÃO (ESTRELAS)
    // =======================================================
    const stars = document.querySelectorAll('.happy-stars .star-icon');
    const msgLogin = document.getElementById('msg-login-rating');

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            if (!logado) {
                msgLogin.style.display = 'block';
                return;
            }
            const notaNumero = document.querySelector('.nota-numero');
            if (notaNumero) notaNumero.innerText = (index + 1).toFixed(1);
            stars.forEach((s, i) => {
                s.classList.toggle('active', i <= index);
                s.classList.toggle('inactive', i > index);
            });
        });
    });

    // =======================================================
    // 4. LÓGICA DO CUPOM DE DESCONTO
    // =======================================================
    const btnCupom = document.getElementById('btn-aplicar-cupom');
    const inputCupom = document.getElementById('input-cupom');
    const msgCupom = document.getElementById('msg-cupom');
    const precoFinal = document.getElementById('preco-final');

    btnCupom?.addEventListener('click', () => {
        const cupomDigitado = inputCupom.value.trim().toUpperCase();
        if (cupomDigitado === 'QUIMERA15') {
            let valorBase = parseFloat(precoFinal.getAttribute('data-valor'));
            let valorNovo = (valorBase * 0.85).toFixed(2);
            let valorFormatado = parseFloat(valorNovo).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            precoFinal.setAttribute('data-valor', valorNovo); // atualiza o valor real usado na URL
            precoFinal.innerText = 'R$ ' + valorFormatado;
            msgCupom.innerText = "Cupom aplicado! (-15%)";
            msgCupom.style.color = "#4CAF50";
            inputCupom.disabled = true;
            btnCupom.disabled = true;
        } else {
            msgCupom.innerText = "Cupom inválido.";
            msgCupom.style.color = "#e50914";
        }
    });
});