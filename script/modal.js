'use strict'

const openModal = document.querySelector('.mobile-open-modal');
const closeModal = document.querySelector('.mobile-close-modal');
const overlay = document.querySelector('.overlay');
const menu = document.querySelector('.menu');

const connexionBtn = document.querySelector('.connexion-btn');
const connexionLink = document.querySelector('.connexion-link')
const connexionMenu = document.querySelector('.connexion-menu')


//Modal Menu
openModal.addEventListener('click', function(e) {
    overlay.classList.remove('hidden');
    menu.classList.remove('hidden');
});

//Modal Connexion
connexionBtn.addEventListener('click', function(e) {
        overlay.classList.remove('hidden');
        connexionMenu.classList.remove('hidden');
});

connexionLink.addEventListener('click', function(e) {
    connexionMenu.classList.remove('hidden');
    menu.classList.add('hidden');
})


//Close Menus
overlay.addEventListener('click', function(e) {
    overlay.classList.add('hidden');
    menu.classList.add('hidden');
    connexionMenu.classList.add('hidden')
});

closeModal.addEventListener('click', function(e) {
    overlay.classList.add('hidden');
    menu.classList.add('hidden');
    connexionMenu.classList.add('hidden')
});