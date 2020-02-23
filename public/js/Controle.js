/* Le fichier "contrôle" gère tout les autres fichiers js. 

/* ---------------------------------------------------------------------------- */

class Controle {
	constructor() { // Le but ici est d'instancier et/ou stocker tous nos objets utiles au fonctionnement de l'application "Open Bike".
		this.newSlider = new Slider;
	};

	initCont() { 
		this.newSlider.initControles(); // Initialisez les controles du slider		
	};

	
	
};

/* ---------------------------------------------------------------------------- */

var start = new Controle; 
start.initCont();