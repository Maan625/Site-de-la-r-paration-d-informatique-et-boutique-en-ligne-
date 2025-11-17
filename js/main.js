const btn = document.getElementById("button_list");
const div_link = document.getElementById("navbarToggleExternalContent");
const image_site = document.getElementById("image_de_site");
const input_search = document.getElementById("input_search");

 
btn.addEventListener("click", () => {
  // image_site.style.opacity = "0";
  div_link.classList.toggle("show");
  input_search.style.display = "none";
   


});
window.addEventListener("click", (e) => {
  if (!div_link.contains(e.target) && !btn.contains(e.target)) {
    div_link.classList.remove("show");
  input_search.style.display = "";

  
  }
})

const formaulaire = document.getElementsByClassName("form_contact");
const formaulaire_deja = document.getElementsByClassName("form_contact_deja");
 const checkbox = document.getElementById("checkbox");

checkbox.addEventListener("change", () => {
  if (checkbox.checked) {
    formaulaire[0].style.display = "none";
    formaulaire_deja[0].style.display = "block";
  } else {
    formaulaire[0].style.display = "block";
    formaulaire_deja[0].style.display = "none";
  }
});









 


function changerMode() {
  const body = document.body; // Récupère l'élément <body> de la page

  const bouton = document.getElementById('modeButton'); // Récupère le bouton avec l'ID "modeButton"



  body.classList.toggle("dark-mode");  // Ajoute la classe "dark-mode" si elle n'existe pas, ou la supprime si elle existe déjà


  if (body.classList.contains("dark-mode"))   // Si le mode sombre est activé

  {
    bouton.innerText = "☀️";   //  // Change le texte du bouton en "Mode jour" avec une icône de soleil


  } else     // Si le mode sombre est désactivé

  {
    bouton.innerText = "🌙"; // Change le texte du bouton en "Mode nuit" avec une icône de lune

  }
}