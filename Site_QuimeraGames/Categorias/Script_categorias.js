document.addEventListener('DOMContentLoaded', () => {

    // 1. Menu Sanfona
    const botoesFiltro = document.querySelectorAll('.btn-filtro-toggle');
    botoesFiltro.forEach(botao => {
        botao.addEventListener('click', () => {
            const conteudo = botao.nextElementSibling;
            const seta = botao.querySelector('.seta');
            conteudo.classList.toggle('ativo');
            seta.style.transform = conteudo.classList.contains('ativo') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    });

    // 2. Lógica de Filtragem
    const inputBusca = document.getElementById('input-filtro-nome');
    const checkboxes = document.querySelectorAll('.filtro-checkbox');
    const jogosCards = document.querySelectorAll('.jogo-card');

    function aplicarFiltros() {
        const termoDigitado = inputBusca ? inputBusca.value.toLowerCase().trim() : '';
        const chkGratis = document.getElementById('chk-gratis')?.checked;
        const chkAte50 = document.getElementById('chk-ate50')?.checked;
        const chkDesconto = document.getElementById('chk-desconto')?.checked;

        jogosCards.forEach(card => {
            const tituloJogo = card.getAttribute('data-titulo');
            const isGratis = card.getAttribute('data-gratis') === 'true';
            const isAte50 = card.getAttribute('data-ate50') === 'true';
            const hasDesconto = card.getAttribute('data-desconto') === 'true';

            let mostrar = true;

            // Filtro de Texto
            if (termoDigitado !== '' && !tituloJogo.includes(termoDigitado)) {
                mostrar = false;
            }

            // Filtro de Preço
            if (mostrar && (chkGratis || chkAte50 || chkDesconto)) {
                let atendeFiltroPreco = false;
                if (chkGratis && isGratis) atendeFiltroPreco = true;
                if (chkAte50 && isAte50) atendeFiltroPreco = true;
                if (chkDesconto && hasDesconto) atendeFiltroPreco = true;

                if (!atendeFiltroPreco) mostrar = false;
            }

            card.style.display = mostrar ? 'flex' : 'none';
        });
    }

    if (inputBusca) inputBusca.addEventListener('input', aplicarFiltros);
    checkboxes.forEach(chk => chk.addEventListener('change', aplicarFiltros));
});


function toggleMenu() {
    const menu = document.getElementById("user-menu");
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
}

// fecha se clicar fora
document.addEventListener("click", function (e) {
    const userBox = document.querySelector(".user-box");
    const menu = document.getElementById("user-menu");

    if (!userBox.contains(e.target)) {
        menu.style.display = "none";
    }
});


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

