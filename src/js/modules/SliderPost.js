/* global $ */

import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';

export default class SliderPost {
	constructor() {
		this.$sliders = $('.js-slider-post');
	}

	init() {
		if (!this.$sliders) {
			return false;
		}

		return this.initPlugins();
	}

	initPlugins() {
		this.$sliders.each((i, e) => {
			const $slider = $(e);

			const slider = {
				progressbar: $slider.find('.js-slider-post-progressbar'),
				count: $slider.find('.js-slider-count'),
				next: $slider.find('.js-slider-next'),
				previous: $slider.find('.js-slider-previous'),
			};

			// console.log($slider);

			$slider
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
		});
	}
}
