var $ = require('jquery');

var config = require('../config');
var classes = require('dom-classes');

function SingleMenu() {
    if (!(this instanceof SingleMenu)) {
        return new SingleMenu();
    }

    this.element = document.querySelector('.js-single-menu');

    if (this.element === null || this.element.length === 0) {
        return;
    }

    this.is_open = config.body.$.hasClass('single-menu--is-open');
    this.items = this.element.querySelectorAll('.js-single-menu-item');
    this.thumbnails = this.element.querySelectorAll('.js-single-menu-thumbnail');

    this.setupEvents();
}


SingleMenu.prototype = {

    /**
     * SingleMenu.setupEvents
     */
    setupEvents: function() {

        $(document)
                .on('click.single-menu', '.js-single-menu-button', this.toggle.bind(this))
                .on('keydown.single-menu', $.proxy(function(e) {

                    e.which === 27 && this.close();

                }, this));

        this.items.forEach(function(el) {
            
            var target = el.children[0];
            var id = target.dataset.id;

            $(el).on({
                mouseenter: $.proxy(function () {
                    // console.log('mouseenter');

                    classes.add(this.element, 'is-hover');

                    this.thumbnails.forEach($.proxy(function(thumbnail) {
                        thumbnail.dataset.id === id && classes.add(thumbnail, 'is-active');
                    }, this));

                }, this),
                mouseleave: $.proxy(function () {
                    // console.log('mouseout');

                    classes.remove(this.element, 'is-hover');

                    this.thumbnails.forEach(function(thumbnail) {
                        classes.remove(thumbnail, 'is-active');
                    });

                }, this)
            });

        
        }.bind(this));
    },


    /**
     * Single.toggle
     */
    toggle: function() {
        if (this.is_open) {
            return this.close();
        }

        return this.open();
    },


    /**
     * Single.close
     */
    close: function() {
        if (!this.is_open) {
            return;
        }

        this.is_open = false;

        config.body.$
            .removeClass('single-menu--is-open')
            .trigger('close.single-menu');

        window.app.enableScroll();
    },

    /**
     * Single.open
     */
    open: function() {
        if (this.is_open) {
            return;
        }

        this.is_open = true;

        window.app.disableScroll();
        
        config.body.$
            .addClass('single-menu--is-open')
            .trigger('open.single-menu');        
    },
}

module.exports = SingleMenu;