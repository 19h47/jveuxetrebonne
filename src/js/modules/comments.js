import Accordion from '@19h47/accordion';

export default class Comments {
	constructor() {
		this.rootElement = document.querySelector('.js-comments');
	}

	init() {
		if (null === this.rootElement) {
			return false;
		}

		return this.initPlugins();
	}

	// ajaxComment() {}

	initPlugins() {
		const accordion = new Accordion(this.rootElement);
		accordion.init();
	}
}
