'use strict'

const openModal = document.querySelector('.mobile-open-modal');
const closeModal = document.querySelector('.mobile-close-modal');
const overlay = document.querySelector('.overlay');
const menu = document.querySelector('.menu');

const connexionBtns = document.querySelectorAll('.connexion');
console.log(connexionBtns);
const connexionMenu = document.querySelector('.connexion-menu')


//Modal Menu
openModal.addEventListener('click', function(e) {
    overlay.classList.remove('hidden');
    menu.classList.remove('hidden');
});

//Modal Connexion
connexionBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        overlay.classList.remove('hidden');
        connexionMenu.classList.remove('hidden');
    })
})

[overlay, closeModal].forEach(btn => {
    btn.addEventListener('click', function(e) {
        overlay.classList.add('hidden');
        menu.classList.add('hidden');
        connexionMenu.classList.add('hidden')
    });
});
