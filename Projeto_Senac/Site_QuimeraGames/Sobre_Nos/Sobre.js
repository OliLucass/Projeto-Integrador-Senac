// Função para abrir/fechar o menu de usuário
function toggleMenu() {
    const menu = document.getElementById("user-menu");
    if (menu) {
        // Se estiver escondido mostra, se estiver visível esconde
        if (menu.style.display === "flex") {
            menu.style.display = "none";
        } else {
            menu.style.display = "flex";
        }
    }
}

// Fecha o menu se o usuário clicar em qualquer outro lugar da página
document.addEventListener("click", function (e) {
    const userBox = document.querySelector(".user-box");
    const menu = document.getElementById("user-menu");
    if (userBox && menu && !userBox.contains(e.target)) {
        menu.style.display = "none";
    }
});

// Mantenha seu código anterior dos cards abaixo...
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll('.card-equipe');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('visivel');
        }, 200 * index);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    // Revela os cards um por um com um pequeno atraso
    const cards = document.querySelectorAll('.card-equipe');

    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('visivel');
        }, 200 * index); // Efeito cascata
    });

    // Suporte para clique no celular (opcional)
    cards.forEach(card => {
        card.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                const inner = card.querySelector('.card-inner');
                inner.style.transform = inner.style.transform === 'rotateY(180deg)' ? 'rotateY(0deg)' : 'rotateY(180deg)';
            }
        });
    });
});