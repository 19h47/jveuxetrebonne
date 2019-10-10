/* global $ */

export default class Print {
	constructor() {
		this.$element = $('.js-print');
	}

	init() {
		if (0 === this.$element.length) {
			return;
		}

		this.initEvents();
	}

	initEvents() {
		this.$element.on('click', () => {
			window.print();
		});
	}
}
