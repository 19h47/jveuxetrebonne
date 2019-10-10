/* global $ */

import 'slick-carousel';

export default class SliderIntroduction {
	constructor() {
		this.$sliders = $('.js-slider-sticky-container');
	}

	init() {
		if (!this.$sliders.length) {
			return false;
		}

		return this.initPlugins();
	}

	/**
	 * SliderIntroduction.initPlugins
	 */
	initPlugins() {
		this.$sliders.each((i, e) => {
			const $slider = $(e);
			const $navigation = $(e).find('.js-slider-sticky-next');

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
