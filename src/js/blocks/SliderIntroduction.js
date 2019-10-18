/* global $ */

import 'slick-carousel';
import { AbstractBlock } from 'starting-blocks';

export default class SliderIntroduction extends AbstractBlock {
	constructor(container) {
		super(container, 'SliderIntroduction');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}


	initPlugins() {
		$(this.rootElement).each((i, e) => {
			const $slider = $(e);
			const $navigation = $(e).find('.js-next');

			$slider
				.find('.js-slider-sticky')

				.slick({
					arrows: false,
					useCSS: false,
					fade: true,
					// nextArrow: $(e).find('.js-slider-sticky-next')
				});

			$navigation.on('click', () => {
				$slider.find('.js-slider-sticky').slick('slickNext');
			});
		});
	}
}
