import config from 'js/config';

import { EventTypes } from 'starting-blocks';

export default class Guid {
	constructor() {
		this.container = document.querySelector('.js-guid');
	}

	init() {
		this.initEvents();
	}

	initEvents() {
		window.addEventListener(EventTypes.AFTER_PAGE_BOOT, () => this.onAfterPageBoot());
	}

	onAfterPageBoot() {
		document.addEventListener('keydown', (e) => {
			if ((e.metaKey || e.ctrlKey) && 186 === e.keyCode) {
				this.container.classList.toggle('d-none');
				config.body.el.classList.toggle('position-relative');
			}
		});
	}
}
