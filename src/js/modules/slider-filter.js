var $ = require('jquery');
require('slick-carousel');


/**
 * Slider relationship posts
 */
function SliderFilter() {
    if (!(this instanceof SliderFilter)) {
        return new SliderFilter();
    }

    this.initPlugins();
}


SliderFilter.prototype = {

    /**
     * SliderFilter.initPlugins
     */
    initPlugins: function() {
        var $slider = $('.js-filter');
        
        $slider.find('.js-slider-filter-container').slick({
            // arrows: false,
            nextArrow: $slider.find('.js-slider-filter-next'),
            prevArrow: $slider.find('.js-slider-filter-previous'),
            // centerMode: true,
            // centerPadding: ( 53 * 100 ) / 1440 + '%',
            mobileFirst: true,
            
            responsive: [{
                breakpoint: 992,
                settings: "unslick"
            }]
        });
    }
}

module.exports = SliderFilter;