var $ = require('jquery');
var toggle = require('toggle');

/**
 * Comments
 */
function Comments() {
	if (!(this instanceof Comments)) {
        	return new Comments();
	}

	this.$element = $('.js-comments');

	if (this.$element.length === 0) {
		return;
	}

	this.$button = this.$element.find('[data-toggle-target]');
	this.$title = this.$element.find('.js-comments-title');

	// console.log(this.$button);

	var hash = window.location.hash;

	if (window.location.hash) {
	  	return;
	}

	this.initPlugins();
}

Comments.prototype = {

	ajaxComment: function() {},

	
	initPlugins: function() {

		// console.log(toggle);
		this.$element.find('.js-comments-list').hide();

		toggle.toggle = $.proxy(function ($element) {

			$element.toggle();


			if (this.$button.hasClass('minus')) {

				this.$title.html('montrer');
				this.$button.removeClass('minus').addClass('plus');

				return;
			}

			if (this.$button.hasClass('plus')) {

				this.$title.html('réduire');
				this.$button.removeClass('plus').addClass('minus');

				return;
			}
		}, this);
	}
};


module.exports = Comments;