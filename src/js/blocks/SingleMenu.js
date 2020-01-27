/* global $ */

import { elements } from 'js/config';
import { disableScroll, enableScroll } from 'Utils/scroll';
import { AbstractBlock } from 'starting-blocks';

export default class SingleMenu extends AbstractBlock {
	constructor(container) {
		super(container, 'SingleMenu');
	}

	async init() {
		super.init();

		this.isOpen = elements.body.$.hasClass('single-menu--is-open');
		this.items = this.rootElement.querySelectorAll('.js-single-menu-item');
		this.thumbnails = this.rootElement.querySelectorAll('.js-thumbnail');
	}


	initEvents() {
		super.initEvents();

		$(document)
			.on('click.single-menu', '.js-single-menu-button', () => this.toggle())
			.on('keydown.single-menu', event => {
				if (27 === event.which) {
					this.close();
				}
			});

		this.items.forEach(el => {
			const target = el.children[0];
			const { id } = target.dataset;

			el.addEventListener('mouseenter', () => {
				this.rootElement.classList.add('is-hover');

				this.thumbnails.forEach(thumbnail => {
					if (thumbnail.dataset.id === id) {
						thumbnail.classList.add('is-active');
					}
				});
			});

			el.addEventListener('mouseleave', () => {
				this.rootElement.classList.remove('is-hover');

				this.thumbnails.forEach(thumbnail => thumbnail.classList.remove('is-active'));
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

		elements.body.$
			.removeClass('single-menu--is-open')
			.trigger('close.single-menu');

		enableScroll();
	}


	open() {
		if (this.isOpen) {
			return true;
		}

		this.isOpen = true;

		disableScroll();

		return elements.body.$
			.addClass('single-menu--is-open')
			.trigger('open.single-menu');
	}
}
