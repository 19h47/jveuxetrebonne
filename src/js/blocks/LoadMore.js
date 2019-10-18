/* global wp */
import { AbstractBlock } from 'starting-blocks';

export default class LoadMore extends AbstractBlock {
	constructor(container) {
		super(container, 'LoadMore');
	}

	async init() {
		super.init();

		this.$container = this.rootElement.querySelector('.js-container');
		this.$containerFooter = this.rootElement.querySelector('.js-container-footer');
		this.$button = this.rootElement.querySelector('.js-button');
		this.$heading = document.querySelector('.js-heading') || false;

		this.filters = [...document.querySelectorAll('.js-filters-button')] || [];

		this.term_id = this.$button.getAttribute('data-term-id') || false;
		this.count = this.$button.getAttribute('data-count');
		this.posts_per_page = this.$button.getAttribute('data-posts-per-page') || 3;
		this.post_template = this.$button.getAttribute('data-template') || 'tease';
		this.offset = this.$button.getAttribute('data-offset') || 9;
		this.exclude = this.$button.getAttribute('data-exclude');

		this.update();

		this.filters.forEach((el) => {
			this.initFilters(el);
		});
	}


	initEvents() {
		super.initEvents();

		this.$button.addEventListener('click', () => {
			this.load()
				.then(response => response.text())
				.then(this.append.bind(this))
				.finally(this.update.bind(this));
		});
	}


	initFilters(el) {
		el.addEventListener('click', () => {
			if (this.term_id === el.getAttribute('data-term-id')) {
				return false;
			}

			// Remove all `is-active` classes
			this.filters.forEach((filter) => {
				filter.classList.remove('is-active');
			});

			el.classList.add('is-active');

			this.offset = 0;
			this.term_id = el.getAttribute('data-term-id');
			this.slug = el.getAttribute('data-slug');
			this.count = el.getAttribute('data-count');
			this.posts_per_page = el.getAttribute('data-post-per-page');
			this.description = el.getAttribute('data-description');

			window.history.pushState('', '', `${wp.current_url}/${this.slug}`);

			this.$heading.querySelector('span').innerHTML = 'Loading';

			return this.load()
				.then(response => response.text())
				// then replace result to the container
				.then(this.replace.bind(this))
				// finally update things
				.finally(this.update.bind(this));
		});
	}


	/**
	 * LoadMore.load
	 */
	load() {
		const url = new URL(window.wp.ajax_url);
		const params = {
			action: 'ajax_load_posts',
			offset: this.offset,
			exclude: this.exclude,
			nonce: wp.nonce,
		};

		if (this.term_id) {
			params.term_id = this.term_id;
		}

		if (this.posts_per_page) {
			params.posts_per_page = this.posts_per_page;
		}

		if (this.post_template) {
			params.post_template = this.post_template;
		}

		Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

		const init = {
			method: 'post',
		};

		// lock everything before the request
		this.on();

		return fetch(url, init);
	}


	replace(html) {
		if (!html) {
			return false;
		}

		this.$heading.querySelector('span').innerHTML = this.description;
		this.$container.innerHTML = html;

		return true;
	}


	/**
	 * LoadMore.append
	 */
	append(html) {
		if (!html) {
			return;
		}

		this.$container.innerHTML += html;
	}


	/**
	 * LoadMore.update
	 */
	update() {
		this.offset = this.$container.querySelectorAll('.js-load-more-post').length;

		this.$button.setAttribute('data-offset', this.offset);
		this.$button.setAttribute('data-term-id', this.term_id);
		this.$button.setAttribute('data-count', this.count);

		if (this.$button && (this.offset < this.count)) {
			this.$containerFooter.style.setProperty('display', 'block');
		}

		// ensure everything is unlocked
		this.off.call(this);
	}


	on() {
		if (this.$container) {
			this.$container.classList.add('is-loading');
		}
	}


	off() {
		if (this.$container) {
			this.$container.classList.remove('is-loading');
		}
	}
}
