/* global $, feature */

import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';


export default class SliderRichContent {
	constructor() {
		this.$sliders = $('.js-slider-rich-content');
	}

	init() {
		if (!this.$sliders) {
			return false;
		}

		return this.initPlugins();
	}

	/**
	 * SliderRichContent.initPlugins
	 */
	initPlugins() {
		if (feature.matchMedia && window.matchMedia('(max-width: 991px)').matches) {
			$('.js-slider-rich-content-close').on('click', () => {
				$('body').toggleClass('aside--is-open');
			});
		}

		const afterPrint = () => {
			// console.info('afterPrint');
		};

		const beforePrint = () => {
			// console.info('beforePrint');
		};

		if (window.matchMedia) {
			const mediaQueryList = window.matchMedia('print');
			mediaQueryList.addListener((mql) => {
				if (mql.matches) {
					beforePrint();
				} else {
					afterPrint();
				}
			});
		}

		const $content = $('.js-single-content');
		const $links = $content.find('.js-rich-content-link');

		this.$sliders.each((i, e) => {
			const $slider = $(e);

			const slider = {
				progressbar: $slider.find('.js-slider-rich-content-progressbar'),
				count: $slider.find('.js-slider-rich-content-count'),
				next: $slider.find('.js-slider-rich-content-next'),
				previous: $slider.find('.js-slider-rich-content-previous'),
			};


			$slider
				.find('.js-slider-rich-content-container')

				.on('init', (event, slick) => {
					if ($links[0]) {
						$links[0].classList.add('is-active');
					}

					$links.each((i, link) => { // eslint-disable-line no-shadow
						const $link = $(link);
						const { id } = $link[0].dataset;
						// console.log(id);

						$link[0].addEventListener('mouseenter', () => {
							$links.each((i, link) => { // eslint-disable-line no-shadow
								$(link).removeClass('is-active');
							});

							$link.addClass('is-active');

							const index = $slider.find(`[data-id=${id}]`).index();

							slick.goTo(parseInt(index, 10));
						});

						if (feature.matchMedia && window.matchMedia('(max-width: 991px)').matches) {
							$link[0].addEventListener('click', () => {
								$('body').toggleClass('aside--is-open');
							});
						}
					});
				})

				.on('init afterChange', (event, slick) => {
					sliderNavigation({ slider, count: slick.slideCount, current: slick.currentSlide });

					$links.each((index, link) => {
						$(link).removeClass('is-active');
					});

					if ($links[slick.currentSlide]) {
						$links[slick.currentSlide].classList.add('is-active');
					}
				})
				.slick({
					// arrows: false,
					useCSS: false,
					adaptiveHeight: false,
					fade: true,
					nextArrow: slider.next,
					prevArrow: slider.previous,
					accessibility: false,
				});
		});
	}
}
