function onLieu(e){
    // recupérer la valeur de select
    let id = e.value;
    // créer une requette http
    const http = new XMLHttpRequest();
    // spécifier le type de reponse en json
    http.responseType="json";
    // la fonction se déclenche une fois que le serveur a repondu
    http.onload = function(){
        /*                console.log(this.response);
                        return;*/
        const data = JSON.parse(this.response);
        document.getElementById("rue").innerHTML = data.rue;
        document.getElementById("ville").innerText = data.ville;
        document.getElementById("cp").innerText = data.cp;
    };
    // choisir la méthode et l'URL
    http.open("GET","/get-adresse/"+id);
    // envoyer la requete http vers le serveur
    http.send();
}

window.addEventListener("load", function(event) {
    onLieu(document.getElementById("update_sortie_lieu"));
});

//fonction pour changer les lieux disponibles selon la sélection de la ville :

function onVille(v){

    recupérer la valeur de option
    let id = v.value;
    // créer une requette http
    const http = new XMLHttpRequest();
    // spécifier le type de reponse en json
    http.responseType="json";
    // la fonction se déclenche une fois que le serveur a repondu
    http.onload = function(){
        console.log(this.response);
        // const data = JSON.parse(this.response);
        // document.getElementById("lieu").innerHTML = data.lieu;

    };
    // choisir la méthode et l'URL
    http.open("GET","/get-lieux/");
    // envoyer la requete http vers le serveur
    http.send();
}

// window.addEventListener("load", function(event) {
//     onVille(document.getElementById("update_sortie_ville"));
// });

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

//gros javascript pompé sur projet précédent pour faire apparaître une image


