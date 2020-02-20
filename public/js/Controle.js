/* Le fichier "contrôle" gère tout les autres fichiers js. 

/* ---------------------------------------------------------------------------- */

class Controle {
	constructor() { // Le but ici est d'instancier et/ou stocker tous nos objets utiles au fonctionnement de l'application "Open Bike".
		this.newSlider = new Slider;
	};

	initCont() { 
		this.newSlider.initControles(); // Initialisez les controles du slider

		// Recupère la promesse contenant les donnees en provenance de l'API "JC Decaux"
		this.initRecupDonnees()
		.then(() => {
			this.newGestionnaireAffichage.initAffichage();
		}); // Le reste du fonctionnement de l'application depend maintenant du fonctionnement interne des objets instancies et du "sessionStorage"
	};

	
	
};

/* ---------------------------------------------------------------------------- */

var start = new Controle; 
start.initCont();