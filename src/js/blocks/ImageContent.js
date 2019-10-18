/* global $ */

import { AbstractBlock } from 'starting-blocks';

export default class ImageContent extends AbstractBlock {
	constructor(container) {
		super(container, 'ImageContent');
	}

	async init() {
		super.init();

		this.$container = $('.js-single-content');
		this.$image = $(this.rootElement.querySelector('.js-image'));

		return this.onResize();
	}

	onResize() {
		super.onResize();

		this.$image.width(this.$container.width());
	}
}
