import config from 'js/config';

export default class Guid {
	constructor() {
		this.rootElement = document.querySelector('.js-guid');
	}

	init() {
		if (null === this.rootElement) {
			return false;
		}

		document.addEventListener('keydown', (e) => {
			if ((e.metaKey || e.ctrlKey) && 186 === e.keyCode) {
				this.rootElement.classList.toggle('d-none');
				config.body.el.classList.toggle('position-relative');
			}
		});

		return true;
	}
}
