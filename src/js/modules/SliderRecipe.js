/* global $ */

import 'slick-carousel';

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
				count: $slider.find('.js-slider-recipe-count'),
				next: $slider.find('.js-slider-recipe-next'),
				previous: $slider.find('.js-slider-recipe-previous'),
			};

			// console.log($slider);

			$slider
				.find('.js-slider-recipe-container')
				.on('init afterChange', (event, slick) => {
					const count = slick.slideCount;
					const current = slick.currentSlide;

					const calc = (current / (count - 1)) * 100;

					slider.progressbar
						.css('background-size', `${calc}% 100%`)
						.attr('aria-valuenow', calc);

					slider.count[0].dataset.count = current + 1;

					slider.count[0].innerHTML = `/${count}`;
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
