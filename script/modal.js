'use strict'

const openModal = document.querySelector('.mobile-open-modal');
const closeModal = document.querySelector('.mobile-close-modal');
const overlay = document.querySelector('.overlay');
const menu = document.querySelector('.menu');

console.log(openModal, closeModal, overlay, menu);

openModal.addEventListener('click', function(e) {
    overlay.classList.remove('hidden');
    menu.classList.remove('hidden');
    console.log(this);
});

[overlay, closeModal].forEach(btn => {
    btn.addEventListener('click', function(e) {
        overlay.classList.add('hidden');
        menu.classList.add('hidden');
        console.log(this);
    });
});
