/////////////////////////////////////// custom le style du menu dropdown de select > option ///////////////////////////////////////
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

    /* crée une div qui contient la liste option en tant que groupe */
    selectHide = document.createElement("DIV");
    selectHide.setAttribute("class", "select-items select-hide");

    for (let j = 1; j < lengthSelElem; j++) {

    // pour chaque elements individuelle dans option, crée une div et recupère leurs inner html
        
        optionItems = document.createElement("DIV");
        optionItems.innerHTML = selElem.options[j].innerHTML;
        optionItems.addEventListener("click", function(e) {

            /* permet de changer l'elements affiché après avoir cliqué sur un elements de la liste*/
            let y, select, previousSelect, selectLength, yl;

            select = this.parentNode.parentNode.getElementsByTagName("select")[0]; // permet de stocker l'HTMLElement select du group dont il fait partie
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

        /* lorsque l'on ouvre un select les autres select ouvert se ferme */

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


/////////////////////////////////////// search bar dynamique (AJAX) ///////////////////////////////////////

// la function est appelé avec comme parametre le contenu de la search bar
function showHint(srch) {
    if (srch.length == 0) {
        // si la search bar est vide, le menu n'est pas affiché 
        textHint.innerHTML = "";    
        textHint.classList.remove("dropDownMenuHint");
        
    } else {
        // si la search bar a du contenu, une requête XML est faite et stocké comme object dans une variable
        const xmlhttp = new XMLHttpRequest();

        // quand la requête à terminé de charger, textHint(la div ou le resultat de la recherche est affiché) aura comme contenu le resultat de la requête
        xmlhttp.onload = function() {
            textHint.innerHTML = this.responseText;
        }
        
        // une requête GET est crée dans le xml, elle renvoie vers une action "search" et le contenu de la search bar
        xmlhttp.open("GET", "index.php?action=search&srch=" + srch);

        // envoie la requête qui sera intercepté par l'index
        xmlhttp.send();

        // et le menu sera affiché 
        textHint.classList.add("dropDownMenuHint");
    }
}

// div qui contient le resultat de la recherche
const textHint = document.getElementById("textHint");

// id de la search bar
const searchBar = document.getElementById("searchInput");

// la function est appelé à chaque fois qu'une touche est 'up' après avoir appuyé dessus
searchBar.addEventListener("keyup", () => showHint(searchBar.value));

