/*----------CREATION DE LA CLASSE SLIDER----------*/

class Slider {
    constructor() {
        // Déclaration des éléments DOM qui va permettre de controler le slider 
        this.gauche = document.getElementById("boutongauche"); // Propriété bouton gauche pour le slider 
        this.droite = document.getElementById("boutondroite"); // Propiété bouton droite pour le slider
        this.pause = document.getElementById("pause"); // Propriété pause pour le slider
        this.play = document.getElementById("play"); // Propriété lecture pour le slider
         // Déclaration des éléments DOM pour l'affichage slider 
        this.images = document.querySelectorAll(".photosslider"); // Propriété des images pour le slider
        this.bulles = document.querySelectorAll(".cercles"); // 
        // Déclaration des variables par défaut
        this.slideAuto = null;
        this.imageActuelle = 0; // position du slider  
        this.vitesse = 5000; // Vitesse du slide défini à 5 secondes
        this.reset();
    };

    reset() { // Création de la boucle pour le slider qui permet de faire un défilement infini
         // on prend toutes les images et les bulles et on les met en état invisible (gris pour les bulles)
        for (let i = 0; i < this.images.length; i++) {
            this.images[i].classList.add("invisible");
            this.bulles[i].style.backgroundColor = "white";
        };
        // Check de la position voulu car si négatif ou supérieur au nombre d'image il faut boucler 
        // (exemple: si imageActuelle == nombre d'image max, on revient à 0 )
        if (this.imageActuelle === this.images.length) {
            this.imageActuelle = 0;
        };
        // (exemple: si imageActuelle == -1 on revient au nombre d'image max )
        if (this.imageActuelle === -1) {
        this.imageActuelle = this.images.length - 1;
        };
        // Methode pour afficher
        this.affichage();
    };

    affichage() {
        // on prend l'index de image actuelle et on lui enleve la classe invisible
        this.images[this.imageActuelle].classList.remove("invisible");
        // on prend l'index de la bulle actuelle et on lui met une couleur
        this.bulles[this.imageActuelle].style.backgroundColor = "orangered";
    };

    rightSlide() { 
        // on incremente +1 à l'index actuelle
        this.imageActuelle++;
        this.reset();
    };

    leftSlide() {
        // on incremente -1 à l'index actuelle
        this.imageActuelle--;
        this.reset();
    };

    clavierSlide(e) { // Fonction pour utiliser le clavier 
        if (e.key === "ArrowLeft") {
            this.leftSlide();
        } else if (e.key === "ArrowRight") {
            this.rightSlide();
        }
    };

    playSlide() { // Pour lancer la lecture du slider
        this.play.classList.add("invisible");
        this.pause.classList.remove("invisible");
        this.slideAuto = setInterval(this.rightSlide.bind(this), this.vitesse); // Fonction qui se répète tout les 5000ms (this.vitesse)
    };

    pauseSlide() { // Pour mettre en pause le slider 
        this.pause.classList.add("invisible");
        this.play.classList.remove("invisible");
        clearInterval(this.slideAuto); // Suppression de la Fonction qui se répète tout les 5000ms (this.vitesse)
    };

    initControles() { // Configuration des évènements 
        this.droite.addEventListener("click", this.rightSlide.bind(this)); // Click droite
        this.gauche.addEventListener("click", this.leftSlide.bind(this)); // Click gauche
        this.play.addEventListener("click", this.playSlide.bind(this));  // Click sur "play"
        this.pause.addEventListener("click", this.pauseSlide.bind(this)); // Click sur "pause"
        document.addEventListener("keydown", this.clavierSlide.bind(this)); 
        this.slideAuto = setInterval(this.rightSlide.bind(this), this.vitesse); // Fonction qui se répète tout les 5000ms (this.vitesse)
    };
};