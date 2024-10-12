'use strict';

const caracteres = document.querySelector('.exigences-crt');
const majuscules = document.querySelector('.exigences-maj');
const special = document.querySelector('.exigences-spc');
const chiffres = document.querySelector('.exigences-chf');
let password = document.getElementById('password-creation');

const caracteresSpeciaux = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '[', ']', '{', '}', ';', ':', ',', '.', '<', '>', '/', '?', '~', '`', '|', '\\'];
const lettresMajuscules = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
const nombres = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0']

function replaceUnvalid(line) {
    let caracStr = line.textContent;
    caracStr = caracStr.replace('❌', '✅');
    line.textContent = caracStr;
}

function replaceValid(line) {
    let caracStr = line.textContent;
    caracStr = caracStr.replace('✅', '❌');
    line.textContent = caracStr;
}

password.addEventListener('input', function () {
    const value = password.value; 

    //Nombre Caractères 
    if (value.length >= 8) {
        replaceUnvalid(caracteres);
    } else {
        replaceValid(caracteres);
    }

    // Caractères Speciaux
    let hasSpecial = false;
    caracteresSpeciaux.forEach(c => {
        if (value.includes(c)) {
            replaceUnvalid(special);
            hasSpecial = true;
        }
    })

    if (!hasSpecial) {
        replaceValid(special);
    }

    //Majuscules
    let hasMaj = false;
    lettresMajuscules.forEach(c => {
        if (value.includes(c)) {
            replaceUnvalid(majuscules);
            hasMaj = true;
        }
    })

    if (!hasMaj) {
        replaceValid(majuscules);
    }

    //Chiffres
    let hasChiffres = false;
    nombres.forEach(c => {
        if (value.includes(c)) {
            replaceUnvalid(chiffres);
            hasChiffres = true;
        }
    })

    if (!hasChiffres) {
        replaceValid(chiffres);
    }
})

