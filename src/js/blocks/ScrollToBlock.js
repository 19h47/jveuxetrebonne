/* global $ */
import { AbstractBlock } from 'starting-blocks';


export default class ScrollToBlock extends AbstractBlock {
	constructor(container) {
		super(container, 'ScrollToBlock');
	}

	async init() {
		super.init();

		this.offset = this.rootElement.getAttribute('data-offset') || false;
	}


	initEvents() {
		super.initEvents();

		let offset = 0;

		switch (this.offset) {
			case 'bottom':
				offset = document.body.scrollHeight;
				break;

			case 'top':
				offset = 0;
				break;
			default:
				break;
		}

		this.rootElement.addEventListener('click', () => {
			$('html, body').animate({
				scrollTop: offset,
			}, 200);
		});
	}
}
