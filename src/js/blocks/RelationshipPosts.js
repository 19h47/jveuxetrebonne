/* global $ */

import 'slick-carousel';
import { AbstractBlock } from 'starting-blocks';

export default class RelationshipPosts extends AbstractBlock {
	constructor(container) {
		super(container, 'RelationshipPosts');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}

	initPlugins() {
		$(this.rootElement).find('.js-container').slick({
			nextArrow: $(this.rootElement).find('.js-next'),
			prevArrow: $(this.rootElement).find('.js-previous'),
			mobileFirst: true,
			responsive: [{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1,
					infinite: false,
				},
			}],
		});
	}
}
