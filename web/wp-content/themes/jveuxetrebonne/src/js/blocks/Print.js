import { AbstractBlock } from 'starting-blocks';

export default class Print extends AbstractBlock {
	constructor(container) {
		super(container, 'LoadMore');
	}

	initEvents() {
		super.initEvents();

		this.rootElement.addEventListener('click', () => {
			window.print();
		});
	}
}
