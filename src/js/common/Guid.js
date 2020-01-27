import config from 'js/config';

import { EventTypes } from 'starting-blocks';


export default class Guid {
	constructor() {
		this.container = document.querySelector('.js-guid');

		this.onAfterPageBoot = this.onAfterPageBoot.bind(this);
	}

	init() {
		this.initEvents();
	}

	initEvents() {
		// console.info('Guid.initEvents');
		window.addEventListener(EventTypes.AFTER_PAGE_BOOT, this.onAfterPageBoot);
	}

	onAfterPageBoot() {
		// console.info('Guid.onAfterPageBoot');

		document.addEventListener('keydown', event => {
			if ((event.metaKey || event.ctrlKey) && 186 === event.keyCode) {
				this.container.classList.toggle('d-none');
				config.body.el.classList.toggle('position-relative');
			}
		});
	}
}
