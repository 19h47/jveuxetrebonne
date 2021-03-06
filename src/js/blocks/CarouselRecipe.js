/* global $ */

import { AbstractBlock } from 'starting-blocks';
import 'slick-carousel';
import sliderNavigation from 'Utils/sliderNavigation';
import mediaBreakpointUp from 'Utils/mediaBreakpointUp';

export default class CarouselRecipe extends AbstractBlock {
	constructor(container) {
		super(container, 'CarouselRecipe');

		this.intersectionObserverOption = { threshold: 1 };
		this.observer = null;
	}

	async init() {
		super.init();

		this.$rootElement = $(this.rootElement);
		this.$container = $('.js-container', this.$rootElement);
		this.$video = $('.js-video', this.$rootElement) ? $('.js-video', this.$rootElement)[0] : false;

		this.desktop = JSON.parse(this.$rootElement.attr('data-carousel-desktop'));

		this.onResize();
	}

	initEvents() {
		super.initEvents();

		this.$rootElement.on('beforeChange', (event, slick, currentSlide) => {
			const video = $(slick.$slides.get(currentSlide)).find('.js-video');

			if (video.length) {
				const promise = video[0].play();

				if (undefined !== promise) {
					promise.then(() => {
						video[0].pause();
					});
				}
			}
		});

		this.$rootElement.on('afterChange', (event, slick, currentSlide) => {
			const video = $(slick.$slides.get(currentSlide)).find('.js-video');

			if (video.length) {
				const promise = video[0].play();

				if (undefined !== promise) {
					promise.then(() => {
						video[0].play();
					});
				}
			}
		});

		this.observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.play();
				}
			});
		});

		this.observer.observe(this.$video);
	}


	initPlugins() {
		if (this.$container.hasClass('slick-initialized')) {
			return false;
		}

		const slider = {
			progressbar: $(this.rootElement).find('.js-progressbar'),
			count: $(this.rootElement).find('.js-count') || false,
			next: $(this.rootElement).find('.js-next'),
			previous: $(this.rootElement).find('.js-previous'),
		};

		return this.$container
			.on('init afterChange', (event, slick) => {
				sliderNavigation({ slider, count: slick.slideCount, current: slick.currentSlide });
			})
			.slick({
				useCSS: false,
				fade: true,
				nextArrow: slider.next,
				prevArrow: slider.previous,
			});
	}

	onResize() {
		super.onResize();

		if (mediaBreakpointUp('md') && this.desktop) {
			return this.initPlugins();
		}

		if (!mediaBreakpointUp('md') && !this.desktop) {
			return this.initPlugins();
		}

		return true;
	}

	destroy() {
		super.destroy();

		this.observer.disconnect();
	}
}
