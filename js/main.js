 let btn = document.getElementById("button_list");
 let nav_list = document.getElementById("nav_list");
 let div_link = document.getElementById("navbarToggleExternalContent");
 
 
 window.addEventListener("click", (e) => {
     if (e.target !== btn && e.target !== div_link) {
        div_link.classList.remove("show");
      }
 });
 
//  btn.addEventListener("click", () => {
//      div_link.classList.toggle("show");
//  });