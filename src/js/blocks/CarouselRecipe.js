/* global $ */

import { AbstractBlock } from 'starting-blocks';
import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';

export default class CarouselRecipe extends AbstractBlock {
	constructor(container) {
		super(container, 'CarouselRecipe');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}


	initPlugins() {
		const slider = {
			progressbar: $(this.rootElement).find('.js-progressbar'),
			count: $(this.rootElement).find('.js-count') || false,
			next: $(this.rootElement).find('.js-next'),
			previous: $(this.rootElement).find('.js-previous'),
		};

		$(this.rootElement)
			.find('.js-container')
			.on('init afterChange', (event, slick) => {
				sliderNavigation({ slider, count: slick.slideCount, current: slick.currentSlide });
			})

			.slick({
				useCSS: false,
				fade: true,
				nextArrow: slider.next,
				prevArrow: slider.previous,
			});
	}
}
