/* global $, wp */

import plyr from 'plyr';

import 'slick-carousel';


export default class SliderCategoryVideos {
	constructor() {
		this.$slider = null;
		this.players = null;
	}

	init() {
		this.initSlick();
		this.initPlyr();
	}


	initPlyr() {
		this.players = plyr.setup({
			iconUrl: wp.current_url,
		});

		let i = 1;
		if (this.players) {
			this.players.forEach((instance) => {
				instance.on('ready', () => {
					const current = instance;

					current.getContainer().setAttribute('id', `plyId-${i}`);
					current.plyId = `plyr-${i}`;

					i += 1;
				});
				instance.on('play', () => {
					const currentPid = instance.plyId;
					this.players.forEach((player) => {
						if (currentPid !== player.plyId) {
							player.pause();
						}
					});
				});
			});
		}
	}


	initSlick() {
		this.$slider = $('.js-slider-category-videos');

		this.$slider.find('.js-slider-category-videos-container')

			.on('beforeChange', () => {
				this.players.forEach((player) => {
					player.pause();
				});
			})

			.slick({
				// arrows: false,
				nextArrow: this.$slider.find('.js-slider-category-videos-next'),
				prevArrow: this.$slider.find('.js-slider-category-videos-previous'),
				mobileFirst: true,
				accessibility: true,
				// slidesToShow: 1,
				// infinite: true,
				// initialSlide: 1,

				responsive: [{
					breakpoint: 992,
					settings: {
						centerMode: true,
						centerPadding: `${(297 * 100) / 1440}%`,
					},
				}],
			});
	}
}
