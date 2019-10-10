
export default class Print {
	constructor() {
		this.rootElement = document.querySelector('.js-print');
	}

	init() {
		if (null === this.rootElement) {
			return false;
		}

		return this.initEvents();
	}

	initEvents() {
		this.rootElement.addEventListener('click', () => {
			window.print();
		});
	}
}
