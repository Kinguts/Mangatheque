/* Le fichier "contrôle" gère tout les autres fichiers js. 

/* ---------------------------------------------------------------------------- */

class Controle {
	constructor() {
		if (document.getElementById("slider")) {
			this.newSlider = new Slider;

			this.initCont();
		}
	};

	initCont() {
		this.newSlider.initControles(); // Initialisez les controles du slider		
	};
};

var start = new Controle; 
