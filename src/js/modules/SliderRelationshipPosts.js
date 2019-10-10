/* global $ */

import 'slick-carousel';

export default class SliderRelationshipPosts {
	constructor() {
		this.$slider = $('.js-relationship-posts');
	}

	init() {
		if (!this.$slider) {
			return false;
		}

		return this.initPlugins();
	}

	/**
	 * SliderRelationshipPosts.initPlugins
	 */
	initPlugins() {
		this.$slider.find('.js-relationship-posts-container').slick({
			// arrows: false,
			nextArrow: this.$slider.find('.js-relationship-posts-next'),
			prevArrow: this.$slider.find('.js-relationship-posts-previous'),
			// centerMode: true,
			// centerPadding: ( 53 * 100 ) / 1440 + '%',
			mobileFirst: true,

			responsive: [{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1,
					infinite: false,
				},
			}],
		});
	}
}
