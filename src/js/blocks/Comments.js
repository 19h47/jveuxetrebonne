import Accordion from '@19h47/accordion';
import { AbstractBlock } from 'starting-blocks';

export default class Comments extends AbstractBlock {
	constructor(container) {
		super(container, 'Comments');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}

	initPlugins() {
		const accordion = new Accordion(this.rootElement);
		accordion.init();
	}
}
