/* global $ */

import 'slick-carousel';

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
				count: $slider.find('.js-slider-post-count'),
				next: $slider.find('.js-slider-post-next'),
				previous: $slider.find('.js-slider-post-previous'),
			};

			// console.log($slider);

			$slider
				.find('.js-slider-post-container')

				.on('init afterChange', (event, slick) => {
					const count = slick.slideCount;
					const current = slick.currentSlide;

					const calc = (current / (count - 1)) * 100;

					slider.progressbar
						.css('background-size', `${calc}% 100%`)
						.attr('aria-valuenow', calc);

					slider.count[0].dataset.count = current + 1;
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
