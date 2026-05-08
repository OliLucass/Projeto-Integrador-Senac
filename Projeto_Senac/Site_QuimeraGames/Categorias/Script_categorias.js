document.addEventListener('DOMContentLoaded', () => {

    // 1. Menu Sanfona (Filtros)
    const botoesFiltro = document.querySelectorAll('.btn-filtro-toggle');
    botoesFiltro.forEach(botao => {
        botao.addEventListener('click', () => {
            const conteudo = botao.nextElementSibling;
            const seta = botao.querySelector('.seta');
            conteudo.classList.toggle('ativo');
            if (seta) {
                seta.style.transform = conteudo.classList.contains('ativo') ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    });

    // 2. Lógica de Filtragem de Jogos
    const inputBusca = document.getElementById('input-filtro-nome');
    const checkboxes = document.querySelectorAll('.filtro-checkbox');
    const jogosCards = document.querySelectorAll('.jogo-card');

    function aplicarFiltros() {
        const termoDigitado = inputBusca ? inputBusca.value.toLowerCase().trim() : '';
        const chkGratis = document.getElementById('chk-gratis')?.checked;
        const chkAte50 = document.getElementById('chk-ate50')?.checked;
        const chkDesconto = document.getElementById('chk-desconto')?.checked;

        jogosCards.forEach(card => {
            const tituloJogo = card.getAttribute('data-titulo') || '';
            const isGratis = card.getAttribute('data-gratis') === 'true';
            const isAte50 = card.getAttribute('data-ate50') === 'true';
            const hasDesconto = card.getAttribute('data-desconto') === 'true';

            let mostrar = true;

            if (termoDigitado !== '' && !tituloJogo.includes(termoDigitado)) mostrar = false;

            if (mostrar && (chkGratis || chkAte50 || chkDesconto)) {
                let atende = false;
                if (chkGratis && isGratis) atende = true;
                if (chkAte50 && isAte50) atende = true;
                if (chkDesconto && hasDesconto) atende = true;
                if (!atende) mostrar = false;
            }

            card.style.display = mostrar ? 'flex' : 'none';
        });
    }

    if (inputBusca) inputBusca.addEventListener('input', aplicarFiltros);
    checkboxes.forEach(chk => chk.addEventListener('change', aplicarFiltros));

    // 3. Lógica das Setas de Categorias
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

    // =========================================================
    // 4. LÓGICA DE ABRIR OS MENUS (BLINDADA CONTRA CONFLITOS)
    // =========================================================
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

    if (btnExplorar && painelExplorar) {
        btnExplorar.addEventListener('click', (e) => {
            // Esse comando bloqueia o Script.js da Home e impede o fechamento imediato
            e.stopImmediatePropagation();

            if (painelCategorias) painelCategorias.classList.remove('show');
            painelExplorar.classList.toggle('show');
            atualizarOverlay();
        });
    }

    if (btnCategorias && painelCategorias) {
        btnCategorias.addEventListener('click', (e) => {
            e.stopImmediatePropagation();

            if (painelExplorar) painelExplorar.classList.remove('show');
            painelCategorias.classList.toggle('show');
            atualizarOverlay();
        });
    }

    // Fechar tudo ao clicar fora com segurança
    document.addEventListener('click', (e) => {
        let fechouAlgo = false;

        // Fecha painel Explorar
        if (painelExplorar && painelExplorar.classList.contains('show') && !painelExplorar.contains(e.target) && e.target !== btnExplorar) {
            painelExplorar.classList.remove('show');
            fechouAlgo = true;
        }

        // Fecha painel Categorias
        if (painelCategorias && painelCategorias.classList.contains('show') && !painelCategorias.contains(e.target) && e.target !== btnCategorias) {
            painelCategorias.classList.remove('show');
            fechouAlgo = true;
        }

        if (fechouAlgo) atualizarOverlay();

        // Fecha menu do usuário (com proteção contra erros)
        const userBox = document.querySelector(".user-box");
        const menu = document.getElementById("user-menu");
        if (userBox && menu && menu.style.display === "flex") {
            if (!userBox.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
        }
    });

});
