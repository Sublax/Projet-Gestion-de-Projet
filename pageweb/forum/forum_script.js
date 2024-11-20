    function filterCountries() {
        // Récupération de la valeur de l'input et conversion en minuscule
        let input = document.getElementById('recherchePays').value.toLowerCase();
        // Sélection de tous les éléments de pays
        let countries = document.getElementsByClassName('country_list');
        
        // Boucle sur chaque pays pour vérifier s'il correspond à la recherche
        for (let i = 0; i < countries.length; i++) {
            let countryName = countries[i].getElementsByClassName('section_pays')[0].innerText.toLowerCase();
            // Affiche ou cache les pays selon la correspondance
            if (countryName.includes(input)) {
                countries[i].style.display = "";
            } else {
                countries[i].style.display = "none";
            }
        }
    }
