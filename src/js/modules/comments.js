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

	this.initPlugins();
}

Comments.prototype = {
	
	initPlugins: function() {

		// console.log(toggle);
		var $button = $("[data-toggle-target]");
		var $title = this.$element.find('.js-comments-title');

		toggle.toggle = function ($element) {

			$element.toggle();

			if ($button.hasClass('minus')) {

				$title.html('montrer');
				$button.removeClass('minus').addClass('plus');

				return;
			}

			if ($button.hasClass('plus')) {

				$title.html('réduire');
				$button.removeClass('plus').addClass('minus');

				return;
			}
		};
	}
};


module.exports = Comments;