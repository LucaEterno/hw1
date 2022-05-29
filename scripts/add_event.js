function apriModale(){
    document.body.classList.add('no-scroll');
    const modale = document.querySelector(".mobileMod");
    modale.classList.add('modaleM');
    modale.addEventListener("click", removeModale);
}

function removeModale(){
    const divModale = document.querySelector(".modaleM");
    divModale.classList.remove('modaleM');
    document.body.classList.remove('no-scroll');
  }

const mobileButton = document.querySelector(".hide_nav");
mobileButton.addEventListener("click", apriModale);