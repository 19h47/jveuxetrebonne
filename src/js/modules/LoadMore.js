/* global $, wp */

export default class LoadMore {
	constructor() {
		this.element = document.querySelector('.js-load-more');
	}

	init() {
		if (!this.element) {
			return false;
		}

		this.container = this.element.querySelector('.js-load-more-container');
		this.button = this.element.querySelector('.js-load-more-button');
		this.filters = document.querySelectorAll('.js-filters-button');
		this.heading = document.querySelector('.js-heading') || null;

		this.tag = this.button.dataset.tag || false;
		this.count = this.button.dataset.count;
		this.posts_per_page = this.button.dataset.postsPerPage || 3;
		this.post_template = this.button.dataset.template || 'tease';
		this.offset = this.button.dataset.offset || 9;
		this.exclude = this.button.dataset.exclude;

		this.update();
		this.initEvents();

		if (this.filters) {
			this.filters.forEach((el) => {
				this.initFilters(el);
			});
		}

		return true;
	}


	/**
	 * LoadMore.setup.events
	 */
	initEvents() {
		this.button.addEventListener('click', () => {
			// load more LoadMore with AJAX
			this.load()
				// then append result to the container
				.then(this.append.bind(this))
				// finally update things
				.done(this.update.bind(this));
		});
	}


	/**
	 * LoadMore.setup.filters
	 */
	initFilters(el) {
		el.addEventListener('click', () => {
			if (this.tag === el.dataset.tag) {
				return false;
			}

			// Remove all `is-active` classes
			this.filters.forEach((filter) => {
				filter.classList.remove('is-active');
			});

			el.classList.add('is-active');

			this.offset = 0;
			this.tag = el.dataset.tag;
			this.count = el.dataset.count;
			this.posts_per_page = el.dataset.postPerPage;
			this.description = el.dataset.description;

			$(this.heading).find('span').html('Loading');

			return this.load()
				// then replace result to the container
				.then(this.replace.bind(this))
				// finally update things
				.done(this.update.bind(this));
		});
	}


	/**
	 * LoadMore.load
	 */
	load() {
		const data = {
			action: 'ajax_load_posts',
			offset: this.offset,
			exclude: this.exclude,
			nonce: wp.nonce,
		};

		if (this.tag) {
			data.tag = this.tag;
		}

		if (this.posts_per_page) {
			data.posts_per_page = this.posts_per_page;
		}

		if (this.post_template) {
			data.post_template = this.post_template;
		}

		// lock everything before the request
		this.on();

		return $.get(window.wp.ajax_url, data);
	}


	/**
	 * LoadMore.replace
	 */
	replace(html) {
		if (!html) {
			return;
		}

		$(this.heading).find('span').html(this.description);

		this.container.innerHTML = html;
	}


	/**
	 * LoadMore.append
	 */
	append(html) {
		if (!html) {
			return;
		}

		$(this.container).append(html);
	}


	/**
	 * LoadMore.update
	 */
	update() {
		this.offset = this.container.querySelectorAll('.js-load-more-post').length;

		this.button.dataset.offset = this.offset;
		this.button.dataset.tag = this.tag;
		this.button.dataset.count = this.count;

		if (this.button) {
			$(this.button).toggle(this.offset < this.count);
		}

		// ensure everything is unlocked
		this.off.call(this);
	}


	/**
	 * LoadMore.lock.on
	 */
	on() {
		if (this.container) {
			this.container.classList.add('is-loading');
		}
	}


	/**
	 * LoadMore.lock.off
	 */
	off() {
		if (this.container) {
			this.container.classList.remove('is-loading');
		}
	}
}
