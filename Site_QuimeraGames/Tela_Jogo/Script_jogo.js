document.addEventListener('DOMContentLoaded', () => {

    // 1. Definições Globais do Jogo
    const infoSessao = document.getElementById('dados-sessao');
    const logado = infoSessao ? infoSessao.getAttribute('data-logado') === 'true' : false;
    const idJogo = infoSessao ? infoSessao.getAttribute('data-jogo') : 0;
    const jaTemDesconto = infoSessao ? infoSessao.getAttribute('data-tem-desconto') === 'true' : false;

    // =======================================================
    // 2. BOTÕES DE AÇÃO (Carrinho, Wishlist, Comprar)
    // =======================================================
    const gerenciarAcao = (acao) => {
        if (!logado) {
            window.location.href = '../Entrar/Entrar.php';
        } else {
            window.location.href = `../Usuario_Logado/acoes_cliente.php?id=${idJogo}&acao=${acao}`;
        }
    };

    document.getElementById('btn-add-carrinho')?.addEventListener('click', () => gerenciarAcao('add_carrinho'));
    document.getElementById('btn-add-wishlist')?.addEventListener('click', () => gerenciarAcao('add_wishlist'));

    document.getElementById('btn-comprar-agora')?.addEventListener('click', () => {
        if (!logado) {
            window.location.href = '../Entrar/Entrar.php';
            return;
        }
        const precoEl = document.getElementById('preco-final');
        const preco = precoEl ? precoEl.getAttribute('data-valor') : '0.00';
        window.location.href = `../Pagamento/pagamento.php?id_jogo=${idJogo}&preco=${preco}`;
    });

    // =======================================================
    // 3. LÓGICA DO CUPOM DE DESCONTO
    // =======================================================
    const btnCupom = document.getElementById('btn-aplicar-cupom');
    const inputCupom = document.getElementById('input-cupom');
    const msgCupom = document.getElementById('msg-cupom');
    const precoFinal = document.getElementById('preco-final');

    if (jaTemDesconto && inputCupom) {
        inputCupom.placeholder = "Promoção ativa (Cupom indisponível)";
        inputCupom.disabled = true;
        if (btnCupom) btnCupom.disabled = true;
    }

    // Variável para garantir que o cliente só aplique 1 vez e o desconto seja exato
    let cupomJaAplicado = false;
    let valorOriginalCupom = precoFinal ? parseFloat(precoFinal.getAttribute('data-valor')) : 0;

    btnCupom?.addEventListener('click', (e) => {
        e.preventDefault(); // Impede a página de "piscar/recarregar" ao clicar

        if (cupomJaAplicado) {
            return; // Se já aplicou, bloqueia novos cliques
        }

        const cupomDigitado = inputCupom.value.trim().toUpperCase();
        let desconto = 0;

        // Verifica a validade do cupom baseado no valor original
        if (cupomDigitado === 'QUIMERA5' && valorOriginalCupom > 0 && valorOriginalCupom < 100) {
            desconto = 0.05;
        } else if (cupomDigitado === 'QUIMERA10' && valorOriginalCupom >= 100) {
            desconto = 0.10;
        }

        if (desconto > 0) {
            // Calcula o novo valor
            let valorNovo = (valorOriginalCupom * (1 - desconto)).toFixed(2);

            // Atualiza o HTML para o cliente ver e para o botão de Compra puxar o valor certo
            precoFinal.setAttribute('data-valor', valorNovo);
            precoFinal.innerText = 'R$ ' + parseFloat(valorNovo).toLocaleString('pt-BR', { minimumFractionDigits: 2 });

            // Mostra mensagem de sucesso
            msgCupom.innerText = `Cupom aplicado! (-${desconto * 100}%)`;
            msgCupom.style.color = "#4CAF50"; // Verde de sucesso

            // Trava o campo para não usarem de novo
            inputCupom.disabled = true;
            btnCupom.disabled = true;
            cupomJaAplicado = true;
        } else {
            // Mensagem de erro caso digitem errado ou tentem burlar a regra
            msgCupom.innerText = "Cupom inválido ou não aplicável.";
            msgCupom.style.color = "#e50914"; // Vermelho de erro
        }
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

            const nota = index + 1;

            // Envia a avaliação para o servidor
            fetch('salvar_avaliacao.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_play=${idJogo}&nota=${nota}`
            }).then(() => {
                // Atualiza a nota exibida na tela
                document.querySelector('.nota-numero').innerText = nota.toFixed(1);
                stars.forEach((s, i) => {
                    s.classList.toggle('active', i <= index);
                    s.classList.toggle('inactive', i > index);
                });
            });
        });
    });
});

function toggleMenu() {
    const menu = document.getElementById("user-menu");
    if (menu) {
        menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
    }
}

// Fecha o menu se clicar fora
document.addEventListener("click", function (e) {
    const userBox = document.querySelector(".user-box");
    const menu = document.getElementById("user-menu");
    if (userBox && menu && !userBox.contains(e.target)) {
        menu.style.display = "none";
    }
});

// ==========================================================
// GAMIFICAÇÃO: MASCOTE BLINDADO
// ==========================================================
document.addEventListener("DOMContentLoaded", () => {
    const spawnDiv = document.getElementById('gamificacao-spawn');
    const imgPath = spawnDiv ? spawnDiv.getAttribute('data-img') : '';

    if (imgPath && imgPath.trim() !== '') {
        console.log("🎲 Sorteio vencido! Tentando carregar mascote:", imgPath);

        setTimeout(() => {
            let mascote = document.createElement('img');
            mascote.src = imgPath;
            mascote.className = 'mascote-gamification';

            // Estilo do Mascote (Absolutamente inquebrável e visível)
            Object.assign(mascote.style, {
                position: 'fixed',
                width: '100px',
                height: '100px',
                borderRadius: '50%',
                zIndex: '2147483647', // O maior z-index possível na web
                cursor: 'pointer',
                border: '4px solid #ffd700',
                boxShadow: '0 0 30px rgba(255, 215, 0, 1)',
                transition: 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                bottom: '30px', // Fica fixo a 30px do final da tela (Livre de textos)
                objectFit: 'cover'
            });

            // Sorteia o lado (Canto Inferior Esquerdo ou Direito) para não atrapalhar o meio da tela
            const lado = Math.random() > 0.5 ? 'left' : 'right';
            if (lado === 'left') {
                mascote.style.left = '30px';
            } else {
                mascote.style.right = '30px';
            }

            // Se a imagem estiver com o nome errado na pasta, ele avisa!
            mascote.onerror = function () {
                console.error("❌ Erro: A foto não foi encontrada no caminho ->", imgPath);
                mascote.remove();
            };

            mascote.style.transform = 'scale(0)';
            document.body.appendChild(mascote);

            // Animação de entrada
            setTimeout(() => mascote.style.transform = 'scale(1)', 100);

            let clicado = false;

            // O Clique de Coleta
            mascote.onclick = () => {
                if (clicado) return;
                clicado = true;

                // Animação de sucção
                mascote.style.transform = 'scale(0) rotate(360deg)';

                fetch('coletar_coin.php', { method: 'POST' })
                    .then(res => res.json())
                    .then(data => {
                        if (data.sucesso) {
                            console.log("🪙 Moeda coletada e salva no banco!");

                            // Atualiza variável global do pagamento.js (Se ela existir nessa página)
                            if (typeof saldoCoinsAtual !== 'undefined') {
                                saldoCoinsAtual++;
                            }

                            // 1. Atualiza Badge do Header
                            const counterTopo = document.getElementById('coin-counter');
                            const boxCoins = document.getElementById('box-coins');
                            if (counterTopo) counterTopo.innerText = parseInt(counterTopo.innerText) + 1;
                            if (boxCoins) {
                                boxCoins.classList.remove('coin-anim');
                                void boxCoins.offsetWidth;
                                boxCoins.classList.add('coin-anim');
                            }

                            // 2. Atualiza formulário do pagamento (Se estiver na tela de pagamento)
                            const txtCheck = document.getElementById('txt-usar-coins');
                            if (txtCheck && typeof saldoCoinsAtual !== 'undefined') {
                                const desc = (saldoCoinsAtual * 0.01).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                txtCheck.innerText = `Usar minhas ${saldoCoinsAtual} moedas (Desconto de R$ ${desc})`;

                                const boxMoedas = document.getElementById('box-usar-moedas');
                                if (boxMoedas) boxMoedas.style.display = 'block';

                                // Se a caixa já estiver marcada, abate do preço na hora!
                                if (typeof usandoCoins !== 'undefined' && usandoCoins && typeof atualizarInterfacePreco === 'function') {
                                    atualizarInterfacePreco();
                                }
                            }
                        }
                    }).catch(err => console.error("❌ Erro no fetch de coins:", err));

                setTimeout(() => mascote.remove(), 500);
            };

            // Vira pó em 5 segundos se não clicar
            setTimeout(() => {
                if (!clicado && mascote && mascote.parentElement) {
                    mascote.style.filter = 'blur(10px) grayscale(100%)';
                    mascote.style.opacity = '0';
                    mascote.style.transform = 'translateY(-50px) scale(1.2)';
                    setTimeout(() => mascote.remove(), 1000);
                }
            }, 5000);

        }, 2000); // Demora 2 segundos para aparecer após abrir a página
    }
});

document.getElementById('btn-add-lista')?.addEventListener('click', () => {
        
            window.location.href = '../Usuario_Logado/meus_pedidos.php';
            return;
        

    });
