'use strict';

//Reveal Sect
const sections = document.querySelectorAll('.section');
const sectionObserver = new IntersectionObserver(revealSct, {
    root: null,
    threshold: 0.15,
});

function revealSct(entries) {
    const [entry] = entries;
    if(entry.isIntersecting) entry.target.classList.remove('section--hidden');
  }
  
    sections.forEach( sct => {
    sct.classList.add('section--hidden')
    sectionObserver.observe(sct);
  });