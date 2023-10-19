function closeAllSelect(elmnt) {
    // A function that will close all select boxes in the document, except the current select box:
    let selectItems, selectSelected, itemsLength, selectedLength, arrNo = [];

    selectItems = document.getElementsByClassName("select-items");

    selectSelected = document.getElementsByClassName("select-selected");

    itemsLength = selectItems.length;

    selectedLength = selectSelected.length;

    for (let i = 0; i < selectedLength; i++) {
        if (elmnt == selectSelected[i]) {
            arrNo.push(i)
        } else {
            selectSelected[i].classList.remove("select-arrow-active");
        }
    }

    for (let i = 0; i < itemsLength; i++) {
        if (arrNo.indexOf(i)) {
            selectItems[i].classList.add("select-hide");
        }
    }
}

let customSelect, customSelectLength, lengthSelElem, selElem, newDiv, selectHide, optionItems;

// Look for any elements with the class "custom-select"
customSelect = document.getElementsByClassName("custom-select");
// stock le nombre de de balise avec cette class
customSelectLength = customSelect.length;

for (let i = 0; i < customSelectLength; i++) {

    selElem = customSelect[i].getElementsByTagName("select")[0];

    lengthSelElem = selElem.length;

    /* For each element, create a new DIV that will act as the selected item: */
    newDiv = document.createElement("DIV");
    newDiv.setAttribute("class", "select-selected");
    newDiv.innerHTML = selElem.options[selElem.selectedIndex].innerHTML; // options récupère l'innerHTML des options de mon/mes selects 
    customSelect[i].appendChild(newDiv);

    /* For each element, create a new DIV that will contain the option list: */
    selectHide = document.createElement("DIV");
    selectHide.setAttribute("class", "select-items select-hide");

    for (let j = 1; j < lengthSelElem; j++) {

    // For each option in the original select element, create a new DIV that will act as an option item:
        
        optionItems = document.createElement("DIV");
        optionItems.innerHTML = selElem.options[j].innerHTML;
        optionItems.addEventListener("click", function(e) {

            /* change selected items: */
            let y, select, previousSelect, selectLength, yl;

            select = this.parentNode.parentNode.getElementsByTagName("select")[0];
            selectLength = select.length;
            previousSelect = this.parentNode.previousSibling;

            for (let i = 0; i < selectLength; i++) {

                if (select.options[i].innerHTML == this.innerHTML) {

                    select.selectedIndex = i;
                    previousSelect.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    yl = y.length;

                    for (let k = 0; k < yl; k++) {
                        y[k].removeAttribute("class");
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

    newDiv.addEventListener("click", function(e) {

        /* When the select box is clicked, close any other select boxes,and open/close the current select box */

        e.stopPropagation();

        closeAllSelect(this);

        this.nextSibling.classList.toggle("select-hide");

        this.classList.toggle("select-arrow-active");
    });
}

/* If the user clicks anywhere outside the select box, then close all select boxes*/
window.addEventListener("click", () => closeAllSelect); 
