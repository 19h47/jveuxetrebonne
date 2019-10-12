/* global $ */

import Plyr from 'plyr';

import 'slick-carousel';


export default class SliderCategoryVideos {
	constructor() {
		this.$slider = null;
		this.players = null;
	}

	init() {
		this.initPlyr();

		if (this.players) {
			this.initSlick();
		}
	}


	initPlyr() {
		const $players = document.querySelectorAll('.js-category-video');

		if (0 === $players.length) {
			return false;
		}

		this.players = new Plyr.setup($players); // eslint-disable-line new-cap

		let i = 1;

		this.players.forEach((instance) => {
			instance.on('ready', () => {
				const current = instance;

				current.elements.container.setAttribute('id', `plyId-${i}`);
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

		return true;
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
						centerPadding: `${(285 * 100) / 1440}%`,
					},
				}],
			});
	}
}
