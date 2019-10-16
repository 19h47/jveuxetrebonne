/* global $ */

import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';

export default class SliderRecipe {
	constructor() {
		this.$sliders = $('.js-slider-recipe');
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
				progressbar: $slider.find('.js-slider-recipe-progressbar'),
				count: $slider.find('.js-slider-count') || false,
				next: $slider.find('.js-slider-next'),
				previous: $slider.find('.js-slider-previous'),
			};

			// console.log($slider);

			$slider
				.find('.js-slider-recipe-container')
				.on('init afterChange', (event, slick) => {
					sliderNavigation({ slider, count: slick.slideCount, current: slick.currentSlide });
				})

				.slick({
					// arrows: false,
					useCSS: false,
					fade: true,
					nextArrow: slider.next,
					prevArrow: slider.previous,
					// accessibility: false
					// mobileFirst: true,

					// responsive: [{
					//     breakpoint: 992,
					//     settings: {
					//         slidesToShow: 2,
					//         slidesToScroll: 2,
					//         infinite: false,
					//     }
					// }]
				});
		});
	}
}
