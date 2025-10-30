 const btn = document.getElementById("button_list");
const div_link = document.getElementById("navbarToggleExternalContent");
const image_site = document.getElementById("image_de_site");


btn.addEventListener("click", (e) => {
  e.stopPropagation();  
  image_site.style.opacity = "0";
});

window.addEventListener("click", (e) => {
  const clickDansMenu = div_link && div_link.contains(e.target);
  const clickDansBouton = btn && btn.contains(e.target);
  
  if (!clickDansMenu && !clickDansBouton) {
    div_link.classList.remove("show");
    image_site.style.opacity = "1";
  }
});

//version mobile
// if(window.matchMedia("(max-width: 600px)").matches){
//     btn.addEventListener("click", () => {
//         image_site.style.opacity = "0";
//     });
// }
// else{
//     window.addEventListener("click", () => {
//         image_site.style.opacity = "1";
//     });
// }
