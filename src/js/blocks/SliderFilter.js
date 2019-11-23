/* global $ */

import 'slick-carousel';
import { AbstractBlock } from 'starting-blocks';

export default class SliderFilter extends AbstractBlock {
	constructor(container) {
		super(container, 'SliderFilter');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}

	initPlugins() {
		const $container = $(this.rootElement);

		$container.find('.js-container').slick({
			nextArrow: $container.find('.js-next'),
			prevArrow: $container.find('.js-previous'),
			mobileFirst: true,
			responsive: [{
				breakpoint: 992,
				settings: {
					slidesToShow: 6,
				},
			}],
		});
	}
}
