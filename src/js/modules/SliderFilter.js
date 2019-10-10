/* global $ */

import 'slick-carousel';

export default class SliderFilter {
	constructor() {
		this.$slider = $('.js-filter');
	}

	init() {
		if (!this.$slider.length) {
			return false;
		}

		return this.initPlugins();
	}

	/**
	 * SliderFilter.initPlugins
	 */
	initPlugins() {
		this.$slider.find('.js-slider-filter-container').slick({
			// arrows: false,
			nextArrow: this.$slider.find('.js-slider-filter-next'),
			prevArrow: this.$slider.find('.js-slider-filter-previous'),
			// centerMode: true,
			// centerPadding: ( 53 * 100 ) / 1440 + '%',
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
