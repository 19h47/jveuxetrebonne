var $ = require('jquery');
require('slick-carousel');


/**
 * Slider category videos
 */
function SliderCategoryVideos() {
    if (!(this instanceof SliderCategoryVideos)) {
        return new SliderCategoryVideos();
    }

    this.initPlugins();
}


SliderCategoryVideos.prototype = {

    /**
     * SliderCategoryVideos.initPlugins
     */
    initPlugins: function() {
        var $slider = $('.js-slider-category-videos');
        
        $slider.find('.js-slider-category-videos-container').slick({
            // arrows: false,
            nextArrow: $slider.find('.js-slider-category-videos-next'),
            prevArrow: $slider.find('.js-slider-category-videos-previous'),
            centerMode: true,
            centerPadding: ( 282 * 100 ) / 1440 + '%'
        });
    }
}

module.exports = SliderCategoryVideos;