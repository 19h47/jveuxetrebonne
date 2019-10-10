/* global $ */

export default class ImageContent {
	constructor() {
		this.$elements = $('.js-image-content-link');
	}

	init() {
		if ('undefined' === this.$elements || 0 === this.$elements.length) {
			return false;
		}

		const $container = $('.js-single-content');
		let containerPaddingLeft = parseInt($container.css('paddingLeft'), 10);

		window.onresize = () => {
			containerPaddingLeft = parseInt($container.css('paddingLeft'), 10);
			this.init();
		};

		this.$elements.each((i, e) => {
			const $element = $(e);
			const img = $element.find('img');
			const elementPositionLeft = $element.position().left;

			$element.find('.image').css({ 'margin-left': (elementPositionLeft - containerPaddingLeft) * -1 });
			img.width($container.width());
		});

		return true;
	}
}
