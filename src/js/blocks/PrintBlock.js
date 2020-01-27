import { AbstractBlock } from 'starting-blocks';

export default class PrintBlock extends AbstractBlock {
	constructor(container) {
		super(container, 'PrintBlock');
	}

	initEvents() {
		super.initEvents();

		this.rootElement.addEventListener('click', () => {
			window.print();
		});
	}
}
