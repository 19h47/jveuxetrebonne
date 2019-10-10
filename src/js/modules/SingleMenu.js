/* global $ */

import config from 'js/config';

export default class SingleMenu {
	constructor() {
		this.rootElement = document.querySelector('.js-single-menu');
	}

	init() {
		if (null === this.rootElement) {
			return false;
		}

		this.isOpen = config.body.$.hasClass('single-menu--is-open');
		this.items = this.rootElement.querySelectorAll('.js-single-menu-item');
		this.thumbnails = this.rootElement.querySelectorAll('.js-single-menu-thumbnail');

		return this.setupEvents();
	}


	setupEvents() {
		$(document)
			.on('click.single-menu', '.js-single-menu-button', () => this.toggle())
			.on('keydown.single-menu', (e) => {
				if (27 === e.which) {
					this.close();
				}
			});

		this.items.forEach((el) => {
			const target = el.children[0];
			const { id } = target.dataset;

			el.addEventListener('mouseenter', () => {
				this.rootElement.classList.add('is-hover');

				this.thumbnails.forEach((thumbnail) => {
					if (thumbnail.dataset.id === id) {
						thumbnail.classList.add('is-active');
					}
				});
			});

			el.addEventListener('mouseleave', () => {
				this.rootElement.classList.remove('is-hover');

				this.thumbnails.forEach((thumbnail) => thumbnail.classList.remove('is-active'));
			});
		});
	}


	toggle() {
		if (this.isOpen) {
			return this.close();
		}

		return this.open();
	}


	close() {
		if (!this.isOpen) {
			return;
		}

		this.isOpen = false;

		config.body.$
			.removeClass('single-menu--is-open')
			.trigger('close.single-menu');

		window.app.constructor.enableScroll();
	}


	open() {
		if (this.isOpen) {
			return true;
		}

		this.isOpen = true;

		window.app.constructor.disableScroll();

		return config.body.$
			.addClass('single-menu--is-open')
			.trigger('open.single-menu');
	}
}
