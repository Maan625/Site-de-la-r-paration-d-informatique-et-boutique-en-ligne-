let btn = document.getElementById("button_list");
let nav_list = document.getElementById("nav_list");
let div_link = document.getElementById("navbarToggleExternalContent");
let div_titre = document.getElementById("div_titre");
let image_site = document.getElementById("image_de_site");


btn.addEventListener("click", () => {
 image_site.style.opacity = "0";
});

 window.addEventListener("click", (e) => {
 if (!div_link.contains(e.target) && e.target !== btn) {
    div_link.classList.remove("show");
    image_site.style.opacity = "1";
}

});
