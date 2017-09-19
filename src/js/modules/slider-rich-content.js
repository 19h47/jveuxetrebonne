var $ = require('jquery');


require('slick-carousel');
require('sticky-kit/dist/sticky-kit.js');


/**
 * Slider rich content
 */
function SliderRichContent() {
    if (!(this instanceof SliderRichContent)) {
        return new SliderRichContent();
    }

    this.initPlugins();
}


SliderRichContent.prototype = {

    /**
     * SliderRichContent.initPlugins
     */
    initPlugins: function() {
         
        if (feature.matchMedia && window.matchMedia('(min-width: 992px)').matches) {
            $('.js-sticky').stick_in_parent({
                parent: '.js-sticky-container',
                bottoming: true
            });


            $('.js-single-aside').stick_in_parent({
                parent: '.js-sticky-container',
                bottoming: true,
            });
        }

        if (feature.matchMedia && window.matchMedia('(max-width: 991px)').matches) {
            $('.js-slider-rich-content-close').on('click', function() {
                $('body').toggleClass('aside--is-open');
            });
        }

        // Unstick and stick after and before print
        var beforePrint = function() {
            // console.info('beforePrint');

            $('.js-single-aside').trigger('sticky_kit:detach');
            $('.js-sticky').trigger('sticky_kit:detach');
        };

        var afterPrint = function() {
            // console.info('afterPrint');

            $('.js-single-aside').stick_in_parent({
                parent: '.js-sticky-container',
                bottoming: true
            });
            $('.js-sticky').stick_in_parent({
                parent: '.js-sticky-container',
                bottoming: true
            });
        };

        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    beforePrint();
                } else {
                    afterPrint();
                }
            });
        }

        // Select all sticky sliders on page
        var $sliders = $('.js-slider-rich-content');
        var $content = $('.js-single-content');
        var $links = $content.find('.js-rich-content-link');

        $sliders.each($.proxy(function(i, e){
            var $slider = $(e);

            var slider = {
                progressbar: $slider.find('.js-slider-rich-content-progressbar'),
                count: $slider.find('.js-slider-rich-content-count'),
                next: $slider.find('.js-slider-rich-content-next'),
                previous: $slider.find('.js-slider-rich-content-previous')
            }


            $slider
                .find('.js-slider-rich-content-container')

                .on('init', function(event, slick) {

                    $links[0].classList.add('is-active');
                    
                    $links.each(function(i, link) {
                        var $link = $(link);
                        var id = $link[0].dataset.id;
                        // console.log(id);

                        $link[0].addEventListener('mouseenter', function() {

                            $links.each(function(i, link) {

                                $(link).removeClass('is-active');
                            });

                            $link.addClass('is-active');

                            var index = $slider.find('[data-id=' + id + ']').index();

                            slick.goTo(parseInt(index));
                        });

                        if (feature.matchMedia && window.matchMedia('(max-width: 991px)').matches) {
                            $link[0].addEventListener('click', function() {
                                $('body').toggleClass('aside--is-open');
                            });
                        }
                    });

                })

                .on('init afterChange', function(event, slick) {   
                    var count = slick.slideCount;
                    var current = slick.currentSlide;
                    var calc = ( current / ( count - 1 ) ) * 100;

                    $links.each(function(i, link) {

                        $(link).removeClass('is-active');
                    });

                    $links[current].classList.add('is-active');

                    slider.progressbar
                        .css('background-size', calc + '% 100%')
                        .attr('aria-valuenow', calc );

                    if (slider.count[0]) {

                        slider.count[0].dataset.count = current + 1;
                    }
                })


                // .on('init', function() {})

                .slick({
                    // arrows: false,
                    useCSS: false,
                    adaptiveHeight: false,
                    fade: true,
                    nextArrow: slider.next,
                    prevArrow: slider.previous,
                    accessibility: false
                });


        }, this));

    }
}

module.exports = SliderRichContent;