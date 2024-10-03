const value = document.getElementById("value");
const input = document.getElementById("note");
const btnFiltre = document.querySelector(".btn-filtres");
const formFiltre = document.querySelector(".form-filtres");

value.textContent = input.value;
input.addEventListener("input", (event) => {
  value.textContent = event.target.value;
});

console.log(btnFiltre);

btnFiltre.addEventListener('click', function(e) {
  formFiltre.classList.toggle('hidden')
})