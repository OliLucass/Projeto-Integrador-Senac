// 1. Funções Globais
function gerenciarTroca(index, elementoMin) {
    const slideContainer = document.getElementById('slide' + index);
    const imgPrincipal = document.getElementById('mainImg' + index);
    const infoBox = document.getElementById('infoBox' + index);

    if (!imgPrincipal || !slideContainer) return;

    const urlCapaOriginal = imgPrincipal.getAttribute('data-capa');
    const urlTemporaria = imgPrincipal.src;

    imgPrincipal.src = elementoMin.src;
    elementoMin.src = urlTemporaria;

    if (imgPrincipal.src === urlCapaOriginal) {
        slideContainer.classList.remove('cenario-ativo');
        infoBox.style.opacity = "1";
        infoBox.style.visibility = "visible";
    } else {
        slideContainer.classList.add('cenario-ativo');
        infoBox.style.opacity = "0";
        infoBox.style.visibility = "hidden";
    }
}

// 2. Lógica Principal
document.addEventListener('DOMContentLoaded', () => {

    // --- LÓGICA DE BUSCA ---
    const inputBusca = document.querySelector('.busca-input input');
    const carrossel = document.querySelector('.carousel-container');
    const secaoDescontos = document.querySelector('.secao');
    const containerGeral = document.querySelector('.container');

    let containerResultados = document.getElementById('resultados-busca');
    if (!containerResultados && containerGeral) {
        containerResultados = document.createElement('div');
        containerResultados.id = 'resultados-busca';
        containerResultados.style.display = 'none';
        containerGeral.appendChild(containerResultados);
    }

    if (inputBusca) {
        inputBusca.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length > 0) {
                if (carrossel) carrossel.style.display = 'none';
                if (secaoDescontos) secaoDescontos.style.display = 'none';
                if (containerResultados) containerResultados.style.display = 'block';

                fetch(`../Index/busca_jogos.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.text())
                    .then(html => {
                        containerResultados.innerHTML = `<h2 style="color:white; margin-bottom:20px;">Resultados para: "${query}"</h2>` + html;
                    })
                    .catch(err => console.error("Erro na busca:", err));
            } else {
                if (carrossel) carrossel.style.display = 'block';
                if (secaoDescontos) secaoDescontos.style.display = 'block';
                if (containerResultados) containerResultados.style.display = 'none';
            }
        });
    }

    // --- LÓGICA DO AUTO-SLIDE ---
    let currentSlide = 1;
    setInterval(() => {
        currentSlide++;
        if (currentSlide > 7) currentSlide = 1;
        const radio = document.getElementById('s' + currentSlide);
        if (radio) {
            radio.checked = true;
        }
    }, 7000);

    // --- LÓGICA DOS BOTÕES EXPLORAR E CATEGORIAS ---
    const btnExplorar = document.getElementById('btn-explorar');
    const btnCategorias = document.getElementById('btn-categorias');
    const painelExplorar = document.getElementById('painel-explorar');
    const painelCategorias = document.getElementById('painel-categorias');
    const overlay = document.getElementById('overlay-escuro');

    function atualizarOverlay() {
        if ((painelExplorar && painelExplorar.classList.contains('show')) ||
            (painelCategorias && painelCategorias.classList.contains('show'))) {
            if (overlay) overlay.classList.add('ativo');
        } else {
            if (overlay) overlay.classList.remove('ativo');
        }
    }

    function togglePainel(painelAtual, painelOutro) {
        if (painelOutro && painelOutro.classList.contains('show')) {
            painelOutro.classList.remove('show');
        }
        if (painelAtual) painelAtual.classList.toggle('show');
        atualizarOverlay();
    }

    if (btnExplorar && painelExplorar) {
        btnExplorar.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePainel(painelExplorar, painelCategorias);
        });
    }

    if (btnCategorias && painelCategorias) {
        btnCategorias.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePainel(painelCategorias, painelExplorar);
        });
    }

    document.addEventListener('click', (e) => {
        let clicouFora = false;
        if (painelExplorar && !painelExplorar.contains(e.target) && e.target !== btnExplorar) {
            painelExplorar.classList.remove('show');
            clicouFora = true;
        }
        if (painelCategorias && !painelCategorias.contains(e.target) && e.target !== btnCategorias) {
            painelCategorias.classList.remove('show');
            clicouFora = true;
        }
        if (clicouFora) atualizarOverlay();
    });

    // --- LÓGICA DAS SETAS DE CATEGORIAS ---
    const gridCategorias = document.getElementById('grid-categorias');
    const setaEsquerda = document.getElementById('seta-esquerda');
    const setaDireita = document.getElementById('seta-direita');

    if (gridCategorias && setaEsquerda && setaDireita) {
        const scrollAmount = 450;
        setaEsquerda.addEventListener('click', (e) => {
            e.preventDefault();
            gridCategorias.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
        setaDireita.addEventListener('click', (e) => {
            e.preventDefault();
            gridCategorias.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }
});

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