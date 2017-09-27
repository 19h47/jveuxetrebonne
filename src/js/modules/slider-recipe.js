var $ = require('jquery');
require('slick-carousel');


/**
 * Slider post
 */
function SliderRecipe() {
    if (!(this instanceof SliderRecipe)) {
        return new SliderRecipe();
    }

    this.initPlugins();
}


SliderRecipe.prototype = {

    /**
     * SliderRecipe.initPlugins
     */
    initPlugins: function() {
        // Select all sticky sliders on page
        var $sliders = $('.js-slider-recipe');

        $sliders.each($.proxy(function(i, e){
            var $slider = $(e);

            var slider = {
                progressbar: $slider.find('.js-slider-recipe-progressbar'),
                count: $slider.find('.js-slider-recipe-count'),
                next: $slider.find('.js-slider-recipe-next'),
                previous: $slider.find('.js-slider-recipe-previous')
            }
            
            // console.log($slider);
            
            $slider
                .find('.js-slider-recipe-container')

                .on('init afterChange', function(event, slick) {   
                    var count = slick.slideCount;
                    var current = slick.currentSlide;
                    
                    var calc = ( current / ( count - 1 ) ) * 100;

                    slider.progressbar
                        .css('background-size', calc + '% 100%')
                        .attr('aria-valuenow', calc );

                    slider.count[0].dataset.count = current + 1;

                    slider.count[0].innerHTML = '/' + count;
                    
                })

                .slick({
                    // arrows: false,
                    useCSS: false,
                    fade: true,
                    nextArrow: slider.next,
                    prevArrow: slider.previous
                    // accessibility: false
                    // mobileFirst: true,

                    // responsive: [{
                    //     breakpoint: 992,
                    //     settings: {
                    //         slidesToShow: 2,
                    //         slidesToScroll: 2,
                    //         infinite: false,
                    //     }
                    // }]
                });


        }, this));
    }
}

module.exports = SliderRecipe;