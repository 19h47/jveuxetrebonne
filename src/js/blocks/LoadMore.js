/* global jveb, wp */
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
		this.count = JSON.parse(this.$button.getAttribute('data-count'));
		this.per_page = JSON.parse(this.$button.getAttribute('data-per-page')) || 3;
		this.post_template = this.$button.getAttribute('data-template') || 'tease';
		this.offset = this.$button.getAttribute('data-offset') || 9;
		this.language = this.$button.getAttribute('data-language');

		this.template = wp.template(this.post_template);

		this.update();

		this.filters.forEach((el) => {
			this.initFilters(el);
		});
	}


	initEvents() {
		super.initEvents();

		this.$button.addEventListener('click', () => {
			this.load()
				.then((response) => response.json())
				.then(this.prepare.bind(this))
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
			this.term_id = JSON.parse(el.getAttribute('data-term-id'));
			this.count = JSON.parse(el.getAttribute('data-count'));
			this.per_page = JSON.parse(el.getAttribute('data-post-per-page'));
			this.description = el.getAttribute('data-description');
			this.slug = el.getAttribute('data-slug');

			window.history.pushState('', '', `${jveb.current_url}/${this.slug}`);

			this.$heading.querySelector('span').innerHTML = 'Loading';
			this.$containerFooter.style.setProperty('display', 'block');

			return this.load()
				.then((response) => response.json())
				.then(this.prepare.bind(this))
				// then replace result to the container
				.then(this.replace.bind(this))
				// finally update things
				.finally(this.update.bind(this));
		});
	}


	load() {
		const url = new URL(`${jveb.api_url}/wp/v2/posts`);
		const params = {
			offset: this.offset,
			nonce: jveb.nonce,
			categories_exclude: 1411,
			lang: this.language,
			status: 'publish',
			sticky: false,
		};

		if (this.term_id) {
			params.categories = this.term_id;
		}

		if (this.per_page) {
			params.per_page = this.per_page;
		}

		Object.keys(params).forEach((key) => url.searchParams.append(key, params[key]));

		const init = {
			method: 'get',
		};

		this.on();

		return fetch(url, init);
	}


	prepare(data) {
		let output = '';

		for (let i = 0; i < data.length; i += 1) {
			output += this.template({
				title: data[i].title.rendered,
				date: data[i].post_date_format,
				link: data[i].link,
				categories: data[i].post_categories.map((category) => `<a href="${category.link}">${category.name}</a>`).join(', '),
				thumbnail: data[i].post_thumbnail_url || '',
			});
		}

		return output;
	}


	replace(output) {
		this.$heading.querySelector('span').innerHTML = this.description;
		this.$container.innerHTML = output;
	}


	append(output) {
		this.$container.innerHTML += output;
	}


	update() {
		this.offset = this.$container.querySelectorAll('.js-load-more-post').length;

		this.$button.setAttribute('data-offset', this.offset);
		this.$button.setAttribute('data-term-id', this.term_id);
		this.$button.setAttribute('data-count', this.count);

		if (this.$button && (this.offset >= this.count)) {
			this.$containerFooter.style.setProperty('display', 'none');
		}

		// ensure everything is unlocked
		this.off();
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
