var $ = require('jquery');

require('slick-carousel');


/**
 * Slider introduction
 */
function SliderIntroduction() {
    if (!(this instanceof SliderIntroduction)) {
        return new SliderIntroduction();
    }

    this.initPlugins();
}


SliderIntroduction.prototype = {

    /**
     * SliderIntroduction.initPlugins
     */
    initPlugins: function() {
        // Select all sticky sliders on page
        var $sliders = $('.js-slider-sticky-container');

        $sliders.each($.proxy(function(i, e){
            var $slider = $(e);
            var $navigation = $(e).find('.js-slider-sticky-next');


            $slider
                .find('.js-slider-sticky')

                .slick({
                    arrows: false,
                    useCSS: false,
                    fade: true,
                    // nextArrow: $(e).find('.js-slider-sticky-next')
                });

            $navigation.on('click', function() {
                $slider.find('.js-slider-sticky').slick('slickNext');
            });

        }, this));
    }
}

module.exports = SliderIntroduction;
