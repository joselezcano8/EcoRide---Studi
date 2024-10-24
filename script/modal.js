'use strict';

const openModal = document.querySelector('.mobile-open-modal');
const closeModal = document.querySelector('.mobile-close-modal');
const overlay = document.querySelector('.overlay');
const menu = document.querySelector('.menu');

const connexionBtn = document.querySelectorAll('.connexion-btn');
const connexionLink = document.querySelector('.connexion-link');
const connexionMenu = document.querySelector('.connexion-menu');

const alertBtn = document.querySelector('.alert-btn');
const alertContainer = document.querySelector('.alert');

//Close Menus
if (overlay) {
    overlay.addEventListener('click', function(e) {
        overlay.classList.add('hidden');
    menu.classList.add('hidden');
    connexionMenu.classList.add('hidden')
    
    if (avis) {
        avis.classList.add('hidden')
    }
});
}


if (closeModal) {
    closeModal.addEventListener('click', function(e) {
        overlay.classList.add('hidden');
        menu.classList.add('hidden');
        connexionMenu.classList.add('hidden')
    });
}


if (alertBtn) {
    alertBtn.addEventListener('click', function() {
        overlay.classList.add('hidden');
        alertContainer.classList.add('hidden');
    })
}

//Modal Menu
if (openModal) {
    openModal.addEventListener('click', function(e) {
        overlay.classList.remove('hidden');
        menu.classList.remove('hidden');
    });
}

//Modal Connexion
if (connexionBtn) {
    connexionBtn.forEach(btn => 
        btn.addEventListener('click', function(e) {
            overlay.classList.remove('hidden');
            connexionMenu.classList.remove('hidden');
        })
    );
}


if (connexionLink) {
    connexionLink.addEventListener('click', function(e) {
        connexionMenu.classList.remove('hidden');
        menu.classList.add('hidden');
    })
};

