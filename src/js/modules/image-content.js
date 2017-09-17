var $ = require('jquery');


/**
 * Slider rich content
 */
function ImageContent() {
    if (!(this instanceof ImageContent)) {
        return new ImageContent();
    }

    this.$elements = $('.js-image-content-link');

    if ( this.$elements === 'undefined' || this.$elements.length === 0 ) {
        return;
    }

    this.$container = $('.js-single-content');
    this.container_padding_left = parseInt(this.$container.css('paddingLeft'));

    window.onresize = $.proxy(function() {

        this.container_padding_left = parseInt(this.$container.css('paddingLeft'));
        this.init();

    }, this);

    this.init();
}


ImageContent.prototype = {

    /**
     * ImageContent.init
     */
    init: function() {
        // console.log(this.$elements);

        this.$elements.each($.proxy(function(i, e) {
            var $element = $(e)
            var img = $element.find('img');
            var element_position_left = $element.position().left;
            
            $element.find('.image').css({'margin-left': (element_position_left - this.container_padding_left) * -1});
            img.width(this.$container.width());

            
        }, this));
    }
}

module.exports = ImageContent;