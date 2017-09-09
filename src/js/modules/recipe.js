var $ = require('jquery');
var config = require('../config');
var SliderPost = require('./slider-post');
var SliderRecipe = require('./slider-recipe');

function Recipe() {
    if (!(this instanceof Recipe)) {
        return new Recipe();
    }

    this.$parent = $('.js-recipes');

    if (this.$parent.length === 0 || this.$parent === 'undefined') {
        return;
    }

    this.$buttons = this.$parent.find('.js-recipes-button');
    this.id = this.$buttons[0].dataset.id;
    this.$container = this.$parent.find('.js-recipes-container');


    this.$buttons && this.$buttons.each(function(i, el) {


    }.bind(this));
    
    this.setup.event.call(this);

    this.load()
        .then(this.append.bind(this))
        .done(this.update.bind(this));
}


Recipe.prototype = {

    setup: {

        event: function(el) {
            console.info('Recipe.setup.event');

            $('.js-recipes-buttons').on('click', '.js-recipes-button', function(e) {
                this.id = e.currentTarget.dataset.id;

                // Recipe is already load
                if ($(e.currentTarget).hasClass('is-active')) {
                    return;
                }

                // Recipe is currently loading
                if (this.$container.hasClass('is-loading')) {
                    return;
                }

                // Remove `is-active` class from buttons collection
                this.$buttons.each(function(i, el) {
                    $(el).removeClass('is-active');
                });

                // Add `is-active` on current element
                $(e.currentTarget).addClass('is-active');

                // load more LoadMore with AJAX
                this.load()
                    // then append result to the container
                    .then(this.replace.bind(this))
                    // finally update things
                    .done(this.update.bind(this));
                
            }.bind(this));
        }
    },

    load: function() {
        var data = {
            action: 'ajax_load_recipes',
            id: this.id
        };

        // lock everything before the request
        this.lock.on.call(this);

        return $.get(window.wp.ajax_url, data);
    },


    /**
     * Recipe.replace
     */
    replace: function(html) {
        if (!html) {
            return;
        }
        
        $(this.$container).html(html);
    },


    /**
     * Recipe.append
     */
    append: function(html) {
        if (!html) {
            return;
        }

        $(this.$container).append(html);
    },


    update: function() {

        new SliderPost();
        new SliderRecipe();

        // ensure everything is unlocked
        this.lock.off.call(this);
    },


    /**
     * Recipe.lock
     */
    lock: {

        /**
         * Recipe.lock.on
         */
        on: function() {
            // console.log('Recipe.lock.on');
            
            // add loading state to ajax container if exists
            this.$container && $(this.$container).addClass('is-loading');
            
        },


        /**
         * Recipe.lock.off
         */
        off: function() {
            // console.log('Recipe.lock.off');
            
            // remove loading state of ajax container if exists
            this.$container && $(this.$container).removeClass('is-loading');
        }
    }
}

module.exports = Recipe;