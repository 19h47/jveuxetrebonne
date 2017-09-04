var $ = require('jquery');
require('slick-carousel');


/**
 * Slider relationship posts
 */
function SliderRelationshipPosts() {
    if (!(this instanceof SliderRelationshipPosts)) {
        return new SliderRelationshipPosts();
    }

    this.initPlugins();
}


SliderRelationshipPosts.prototype = {

    /**
     * SliderRelationshipPosts.initPlugins
     */
    initPlugins: function() {
        var $slider = $('.js-relationship-posts');
        
        $slider.find('.js-relationship-posts-container').slick({
            // arrows: false,
            nextArrow: $slider.find('.js-relationship-posts-next'),
            prevArrow: $slider.find('.js-relationship-posts-previous'),
            // centerMode: true,
            // centerPadding: ( 53 * 100 ) / 1440 + '%',
            slidesToShow: 2,
            slidesToScroll: 1,
            infinite: false,
        });
    }
}

module.exports = SliderRelationshipPosts;