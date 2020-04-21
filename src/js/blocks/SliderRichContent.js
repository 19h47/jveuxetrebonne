/* global $, feature */

import { AbstractBlock } from 'starting-blocks';
import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';

export default class SliderRichContent extends AbstractBlock {
	constructor(container) {
		super(container, 'SliderRichContent');
	}

	async init() {
		super.init();

		return this.initPlugins();
	}

	/**
	 * SliderRichContent.initPlugins
	 */
	initPlugins() {
		if (feature.matchMedia && window.matchMedia('(max-width: 991px)').matches) {
			$('.js-close').on('click', () => {
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
			mediaQueryList.addListener(mql => {
				if (mql.matches) {
					beforePrint();
				} else {
					afterPrint();
				}
			});
		}

		const $content = $('.js-single-content');
		const $links = $content.find('.js-rich-content-link');
		const slider = {
			progressbar: $(this.rootElement).find('.js-progressbar'),
			count: $(this.rootElement).find('.js-count'),
			next: $(this.rootElement).find('.js-next'),
			previous: $(this.rootElement).find('.js-previous'),
		};

		$(this.rootElement)
			.find('.js-slider-rich-content-container')

			.on('init', (event, slick) => {
				if ($links[0]) {
					$links[0].classList.add('is-active');
				}

				$links.each((i, link) => {
					// eslint-disable-line no-shadow
					const $link = $(link);
					const { id } = $link[0].dataset;

					$link[0].addEventListener('mouseenter', () => {
						$links.each((i, link) => {
							// eslint-disable-line no-shadow
							$(link).removeClass('is-active');
						});

						$link.addClass('is-active');

						const index = $(this.rootElement)
							.find(`[data-id="${id}"]`)
							.parent()
							.parent()
							.index();

						slick.goTo(index);
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
	}
}
