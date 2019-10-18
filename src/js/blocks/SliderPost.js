/* global $ */

import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';
import { AbstractBlock } from 'starting-blocks';

export default class SliderPost extends AbstractBlock {
	constructor(container) {
		super(container, 'Recipes');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}

	initPlugins() {
		const slider = {
			progressbar: $(this.rootElement).find('.js-progressbar'),
			count: $(this.rootElement).find('.js-count'),
			next: $(this.rootElement).find('.js-next'),
			previous: $(this.rootElement).find('.js-previous'),
		};

		$(this.rootElement)
			.find('.js-slider-post-container')

			.on('init afterChange', (event, slick) => {
				sliderNavigation({ slider, count: slick.slideCount, current: slick.currentSlide });
			})

			.slick({
				accessibility: false,
				// arrows: false,
				useCSS: false,
				fade: true,
				nextArrow: slider.next,
				prevArrow: slider.previous,
			});
	}
}
