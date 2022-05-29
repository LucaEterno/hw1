function fetchEvent(){
    fetch("fetch_event.php").then(onResponse).then(eventJson);
}

function onResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function eventJson(json) {
    console.log(json);
   
    const passati = document.querySelector(".passati");
    passati.innerHTML = '';
    const futuri = document.querySelector(".futuri");
    futuri.innerHTML = '';

    for (let i=0; i < json.length; i++){
        if(json[i].time<86399){
            const row1 = document.createElement("div");
            const row2 = document.createElement("div");
            const box = document.createElement("div");
            box.classList.add('buttonBox');

            const eventid = document.createElement("p");
            eventid.classList.add('hide');
            eventid.classList.add('e'+json[i].eventid);
            eventid.textContent = json[i].eventid;

            const title = document.createElement("h3");
            title.textContent = json[i].tipo;

            const followButton = document.createElement("input");
            followButton.type = "button";
            followButton.id = "follow";
            followButton.addEventListener('click', follow);
            followButton.value = "Follow";

            const unfollowButton = document.createElement("input");
            unfollowButton.type = "button";
            unfollowButton.id = "unfollow";
            unfollowButton.addEventListener('click', unfollow);
            unfollowButton.value = "Unfollow";
            unfollowButton.classList.add('hide');
            
            const data = document.createElement("p");
            data.textContent = "Data: " + json[i].data;

            const user = document.createElement("p");
            user.textContent = "Promosso da: " + json[i].user;

            row1.appendChild(eventid);
            row1.appendChild(title);
            box.appendChild(followButton);
            box.appendChild(unfollowButton);
            row1.appendChild(box);
            row2.appendChild(data);
            row2.appendChild(user);
            futuri.appendChild(row1);
            futuri.appendChild(row2);
            fetch("fetch_follow.php?q="+json[i].eventid).then(onResponse).then(partecipazione);
        } else {
            const row1 = document.createElement("div");
            const row2 = document.createElement("div");

            const eventid = document.createElement("p");
            eventid.classList.add('hide');
            eventid.classList.add('e'+json[i].eventid);
            eventid.textContent = json[i].eventid;

            const title = document.createElement("h3");
            title.textContent = json[i].tipo;

            const data = document.createElement("p");
            data.textContent = "Data: " + json[i].data;

            const user = document.createElement("p");
            user.textContent = "Promosso da: " + json[i].user;

            row1.appendChild(eventid);
            row1.appendChild(title);
            row2.appendChild(data);
            row2.appendChild(user);
            passati.appendChild(row1);
            passati.appendChild(row2);
        }
    }
}

function partecipazione(json){
    console.log(json);

    if (json.length>0){
        const n = document.querySelector('.e'+json[0].eventid);
        const followButton = n.parentNode.childNodes[2].childNodes[0];
        followButton.classList.add('hide');
        const unfollowButton = n.parentNode.childNodes[2].childNodes[1];
        unfollowButton.classList.remove('hide');
    } 
}

function follow(event){
    console.log("ADESSO SEGUI QUESTO EVENTO");
    const button = event.currentTarget;

    const eventid = button.parentNode.parentNode.childNodes[0];
    button.classList.add('change');
    const url = encodeURIComponent(eventid.textContent);
    fetch("fetch_follow_add.php?q="+url).then(onResponse).then(cambiaButton);
}

function unfollow(event){
    console.log("NON SEGUI PIÙ QUESTO EVENTO");
    const button = event.currentTarget;

    const eventid = button.parentNode.parentNode.childNodes[0];
    button.classList.add('change');
    const url = encodeURIComponent(eventid.textContent);
    fetch("fetch_follow_remove.php?q="+url).then(onResponse).then(cambiaButton);
}

function cambiaButton(json){
    console.log(json);
    if (json.ok){
        const button = document.querySelector('.change');
        button.classList.remove('change');
        if (button === button.parentNode.firstChild){
            hiddenButton = button.parentNode.childNodes[1];
            button.classList.add('hide');
            hiddenButton.classList.remove('hide');
        } else {
            hiddenButton = button.parentNode.childNodes[0];
            button.classList.add('hide');
            hiddenButton.classList.remove('hide');
        }
    }
  }

fetchEvent();

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