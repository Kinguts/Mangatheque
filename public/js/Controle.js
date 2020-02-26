/* Le fichier "contrôle" gère tout les autres fichiers js. 

/* ---------------------------------------------------------------------------- */

class Controle {
	constructor() { 
		this.newSlider = new Slider;
	};

	initCont() { 
		this.newSlider.initControles(); // Initialisez les controles du slider		
	};	
};

var start = new Controle; 
start.initCont();