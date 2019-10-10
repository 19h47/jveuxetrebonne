/* global $ */

export default class Comments {
	constructor() {
		this.$element = $('.js-comments');
	}

	init() {
		if (0 === this.$element.length) {
			return;
		}

		this.$button = this.$element.find('[data-toggle-target]');
		this.$title = this.$element.find('.js-comments-title');

		// console.log(this.$button);

		// var hash = window.location.hash;

		if (window.location.hash) {
			return;
		}

		this.initPlugins();
	}

	// ajaxComment() {}

	initPlugins() {
		// console.log(toggle);
		this.$element.find('.js-comments-list').hide();
		//
		// toggle.toggle = ($element) => {
		// 	$element.toggle();
		//
		// 	if (this.$button.hasClass('minus')) {
		// 		this.$title.html('montrer');
		// 		this.$button.removeClass('minus').addClass('plus');
		// 	}
		//
		// 	if (this.$button.hasClass('plus')) {
		// 		this.$title.html('réduire');
		// 		this.$button.removeClass('plus').addClass('minus');
		// 	}
		// };
	}
}
