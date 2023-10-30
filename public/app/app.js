/////////////////////////////////////// custom le style du menu dropdown select > option ///////////////////////////////////////
// ferme tous les select ouvert
function closeAllSelect(elmnt) {
    // initialisations variable
    let selectItems, selectSelected, itemsLength, selectedLength, arrNo = [];
    //reçoit les elements de classe select-items
    selectItems = document.getElementsByClassName("select-items");
    // stock le nombre d'elements avec cette class
    itemsLength = selectItems.length;

    //reçoit les elements de classe select-selected
    selectSelected = document.getElementsByClassName("select-selected");
    // stock le nombre d'elements avec cette class
    selectedLength = selectSelected.length;

    // boucle sur le nombres d'elements avec la class select-selected
    for (let i = 0; i < selectedLength; i++) {
        // elmnt est equivalent au select-selected, la valeur est push dans arrNo sinon la class active lui est donné
        if (elmnt == selectSelected[i]) {
            arrNo.push(i)
        } else {
            selectSelected[i].classList.remove("select-arrow-active");
        }
    }

    // boucle sur le nombres d'items dans options
    for (let i = 0; i < itemsLength; i++) {
        if (arrNo.indexOf(i)) {
            //recupère l'index de arrNo est l'utilise pour cacher le selectItems
            selectItems[i].classList.add("select-hide");
        }
    }
}

// initialisations variable
let customSelect, customSelectLength, lengthSelElem, selElem, newDiv, selectHide, optionItems; 
//reçoit les elements de classe select-items
customSelect = document.getElementsByClassName("custom-select");
// stock le nombre d'elements avec cette class
customSelectLength = customSelect.length;

for (let i = 0; i < customSelectLength; i++) {

    selElem = customSelect[i].getElementsByTagName("select")[0];

    lengthSelElem = selElem.length;

    /* pour option une nouvelle div sera crée qui interagira comme option */
    newDiv = document.createElement("DIV");
    newDiv.setAttribute("class", "select-selected");
    newDiv.innerHTML = selElem.options[selElem.selectedIndex].innerHTML; // options récupère l'innerHTML des options de mon/mes selects 
    customSelect[i].appendChild(newDiv);

    // crée une div qui contient la liste d'option
    selectHide = document.createElement("DIV");
    selectHide.setAttribute("class", "select-items select-hide");

    for (let j = 1; j < lengthSelElem; j++) {

    // pour chaque elements individuelle dans option, crée une div et recupère leurs inner html
        optionItems = document.createElement("DIV");
        optionItems.innerHTML = selElem.options[j].innerHTML;

        optionItems.addEventListener("click", function(e) {
            // permet de changer l'elements affiché après avoir cliqué sur un elements de la liste
            let sameSelected, select, previousSelect, selectLength, sameSelLength;

            select = this.parentNode.parentNode.getElementsByTagName("select")[0]; // l'HTMLElement <select>
            selectLength = select.length;
            previousSelect = this.parentNode.previousSibling;

            for (let i = 0; i < selectLength; i++) {

                if (select.options[i].innerHTML == this.innerHTML) {

                    select.selectedIndex = i;
                    previousSelect.innerHTML = this.innerHTML;
                    sameSelected = this.parentNode.getElementsByClassName("same-as-selected");
                    sameSelLength = sameSelected.length;

                    for (let k = 0; k < sameSelLength; k++) {
                        sameSelected[k].removeAttribute("class");
                    }

                    this.setAttribute("class", "same-as-selected");
                    break;
                }
            }
            previousSelect.click();
        });
        selectHide.appendChild(optionItems);
    }

    customSelect[i].appendChild(selectHide);

    newDiv.addEventListener("click", function(e) { // agis sur un mouseEvent 

        // lorsque l'on ouvre un select les autres select ouvert se ferme 

        e.stopPropagation(); // agis sur un mouseEvent (click)

        closeAllSelect(this);
        // this. - ici this. est le HTMLElement newDiv
        // toggle select-hide - cache celui qui est visible et rends visible celui qui est caché
        this.nextSibling.classList.toggle("select-hide");
        // puis change les positions des flèches 
        this.classList.toggle("select-arrow-active");
    });
}

// appel la function pour tout fermer lors d'un clic dans la fenetre
window.addEventListener("click", closeAllSelect); 

if (document.getElementById("updateGenre")) {
    const updateGenre = document.getElementById("updateGenre");
    updateGenre.addEventListener("click", updateGenreOnClick);
}

function updateGenreOnClick() {
    const genresFilm = document.getElementsByClassName("genreFilm");
    for (let i = 0; i < genresFilm.length; i++) {
        const openGenreFilm = genresFilm[i];
        let hrefTarget = openGenreFilm.href;

        function removeEventListener() {
            openGenreFilm.style.color = "var(--primary-font-color)"; 
            document.getElementById("updateGenre").style.backgroundColor = "var(--primary-background-color)";
            openGenreFilm.href = hrefTarget;
        } 

        openGenreFilm.href = hrefTarget.replace("detailsGenre", "deleteGenreFilm");

        openGenreFilm.style.color = "red";
        document.getElementById("updateGenre").style.backgroundColor = "red";
        
        setTimeout(removeEventListener, 3000);
    }
};

/////////////////////////////////////// search bar dynamique (AJAX) ///////////////////////////////////////

// la function est appelé avec comme parametre le contenu de la search bar
const textHint = document.getElementById("textHint"); // id de la search bar

// future balises <a><p><span></span></p></a> du bloc resultat de la recherche
const linkResult = document.createElement("A"); 
const valueResult = document.createElement("P");
const categoryResult = document.createElement("span");

// la function est appelé avec comme parametre le contenu de la search bar
async function showHint(srch) {
    if (srch.length == 0) {
        // si la search bar est vide, le menu n'est pas affiché 
        textHint.innerHTML = "";    
        textHint.classList.remove("dropDownMenuHint");
        
    } else {
        while(textHint.childNodes.length > 0) {
            textHint.removeChild(child[0]);
        }  
        // appel asynchrone
        const response = await fetch(
            "index.php?action=search&srch=" + srch
            // {
            //     method: "GET",
            //     headers: {},
            //     body: {},
            // }
        );
        
        // on attend le retour de PHP avec le JSON qui contient le resultat de la requête SQL
        const data = await response.json();
        // const data = await JSON.parse(response);

        if(data) {

            data.forEach((info) => {
                const newValueResult = valueResult.cloneNode(); // <a>
                const newLinkResult = linkResult.cloneNode(); // <p>
                const newCategoryResult = categoryResult.cloneNode(); // <span>

                newLinkResult.href = info["link"];
                newValueResult.textContent = info["label"];
                newCategoryResult.textContent = info["category"]+" -> ";

                textHint.appendChild(newLinkResult);
                newLinkResult.appendChild(newValueResult);
                newValueResult.prepend(newCategoryResult);
            });

        } else {

        }

        textHint.classList.add("dropDownMenuHint");
    }
}

// XMLHttpRequest : bas niveau, permet de mettre en place la solution sans aucune contrainte, peu importe le matériel, nécessite uniquement JS
// fetch : librairie incluse dans tous les navigateurs, fonctionnera très bien dans la partie front d'une app, nécessitera d'être importée/installée dans un back
// $.ajax (JQuery) : nécessite JQuery, très semblable à fetch, fait automatiquement la transformation de JSON en objet exploitable
// axios : librairie à importer/installer obligatoirement, met à disposition beaucoup de fonctionnalités autour des requêtes/réponses HTTP

// div qui contient le resultat de la recherche
// const textHint = document.getElementById("textHint");

// id de la search bar
const searchBar = document.getElementById("searchInput");

// la function est appelé à chaque fois qu'une touche est 'up' après avoir appuyé dessus
searchBar.addEventListener("keyup", () => {
    showHint(searchBar.value)
});

// recupère toutes les balises enfants de textHint 
const child = textHint.childNodes;

// ferme le dropdown menu de la bar de recherche et la vide quand on clique dans la page 
window.addEventListener("click", function() {
    if(textHint.classList.contains("dropDownMenuHint")) {
        while(child.length > 0) {
            textHint.removeChild(child[0]);
        }      
        textHint.classList.remove("dropDownMenuHint");
    }
});

/////////////////////////////////////// Confirm delete btn ///////////////////////////////////////

// lors d'un clic sur le deleteBtn une fenêtre confirm() sera ouverte, si TRUE laisse faire, sinon (FALSE) stoppe le GET
const btnAlert = document.getElementsByClassName("deleteBtn");
for (let i = 0; i < btnAlert.length; i++) {
    const openBtnAlert = btnAlert[i];

    openBtnAlert.addEventListener("click", function() {
        // stock le contenu du href de l'element clické
        $hrefTarget = btnAlert[i].parentElement.href;

        //cette function me permet d'appliquer un timeout avant de l'executer avec setTimeout() 
        $updateHref = function() {btnAlert[i].parentElement.href = $hrefTarget };

        $check = confirm("Are you sure you want to delete this ?");
        if (!$check) {
            btnAlert[i].parentElement.setAttribute("href", "#");
            setTimeout($updateHref, 500);
        } else {

        }
    })
}


