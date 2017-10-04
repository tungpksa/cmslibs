/*
 * @Name: jquery.okayNav.js - A progressively responsive navigation
 * @Version: 1.0.1
 *
 * @Copyright (c) 2016 Vergil Penkov
 *
 * Licensed under the MIT license: https://opensource.org/licenses/MIT
 * Project home: https://github.com/VPenkov/okayNav
*/

;(function ( $, window, document, undefined ) {

    // Defaults
    var okayNav = 'okayNav',
        defaults = {
            parent : '', // will call nav's parent() by default
            toggle_icon_class : 'okayNav__menu-toggle',
            toggle_icon_content: '<span /><span /><span />',
            beforeopen : function() {}, // Will trigger before the nav gets opened
            open : function() {}, // Will trigger after the nav gets opened
            beforeclose : function() {}, // Will trigger before the nav gets closed
            close : function() {}, // Will trigger after the nav gets closed
        };

    // Begin
    function Plugin( element, options ) {
        this.options = $.extend( {}, defaults, options) ;
        _okayNav = this; // Plugin

        _invisibleNavState = false; // Is the hidden menu open?
        _options = this.options;

        $document = $(document); // for event triggering
        $body = $('body'); // for controlling the overflow
        $navigation = $(element); // jQuery object

        this.options.parent == '' ? this.options.parent = $navigation.parent() : '';

        // At this point, we have access to the jQuery element and the options
        // via the instance, e.g., $navigation and _options. We can access these
        // anywhere in the plugin.
        _okayNav.init();
    }

    Plugin.prototype = {
        init: function () {
            // Some DOM manipulations
            _okayNav.setupElements($navigation);

            // Cache new elements for further use
            $nav_visible = $navigation.children('.okayNav__nav--visible');
            $nav_invisible = $navigation.children('.okayNav__nav--invisible');
            $nav_toggle_icon = $navigation.children('.' + _options.toggle_icon_class);
            _nav_toggle_icon_width = $nav_toggle_icon.outerWidth(true);
            _last_visible_child_width = 0; // We'll define this later

            // Events are up once everything is set
            _okayNav.initEvents();
        },

        /*
         * Let's setup the elements and attach events
         */
        // Elements
        setupElements: function(el) {
            $body.addClass('okayNav-loaded');

            // Add classes
            $navigation
                .addClass('okayNav loaded')
                .children('ul').addClass('okayNav__nav--visible');

            // Append elements
            $navigation
                .append('<ul class="okayNav__nav--invisible" />')
                .append('<a href="#" class="' + _options.toggle_icon_class + '">' + _options.toggle_icon_content + '</a>')
        },

        // Events
        initEvents: function() {
            // Toggle hidden nav when hamburger icon is clicked
            $document.on('click.okayNav', '.' + _options.toggle_icon_class, function(event) {
                event.preventDefault();
                _okayNav.toggleInvisibleNav();
            });

            // Collapse hidden nav on click outside the header
            $document.on('click.okayNav', function(event) {
                if (_invisibleNavState === true) {
                    var _target = $(event.target);
                    if (!_target.parents().hasClass('okayNav'))
                        _okayNav.closeInvisibleNav();
                }
            });

            $(window).on('load.okayNav resize.okayNav', function(event) {
                _okayNav.recalcNav();
            });
        },

        /*
         * A few methods to allow working with elements
         */
        getParent: function () {
            return _options.parent;
        },

        getVisibleNav: function() { // Visible navigation
            return $nav_visible;
        },

        getInvisibleNav: function() { // Hidden behind the kebab icon
            return $nav_invisible;
        },

        getNavToggleIcon: function() { // Kebab icon
            return $nav_toggle_icon;
        },

        /*
         * Operations
         */
        openInvisibleNav: function() {
            _options.beforeopen.call();
            $nav_toggle_icon.addClass('icon--active');
            $nav_invisible.addClass('nav-open');
            _invisibleNavState = true;
            _options.open.call();
            $document.trigger('okayNav:open');
        },

        closeInvisibleNav: function() {
            _options.beforeclose.call();
            $nav_toggle_icon.removeClass('icon--active');
            $nav_invisible.removeClass('nav-open');
            _invisibleNavState = false;
            _options.close.call();
            $document.trigger('okayNav:close');
        },

        toggleInvisibleNav: function() {
            if (!_invisibleNavState) {
                _invisibleNavState = true;
                _okayNav.openInvisibleNav();
            }
            else {
                _invisibleNavState = false;
                _okayNav.closeInvisibleNav();
            }
        },


        /*
         * Math stuff
         */
        getParentWidth: function(el) {
            var parent = el || _options.parent;
            var parent_width = $(parent).outerWidth(true);

            return parent_width;
        },

        getChildrenWidth: function(el) {
            var children_width = 0;
            $(el).children().each(function() {
                children_width += $(this).outerWidth(true);
            });

            return children_width;
        },

        countNavItems: function(el) {
            var $menu = $(el);
            var items = $('li', $menu).length;

            return items;
        },

        recalcNav: function() {
            var wrapper_width = $(_options.parent).outerWidth(true);
            var nav_full_width = $navigation.outerWidth(true);
            var visible_nav_items = _okayNav.countNavItems($nav_visible);

            var collapse_width = $nav_visible.outerWidth(true) + _nav_toggle_icon_width - 1;
            var expand_width = _okayNav.getChildrenWidth(_options.parent) + _last_visible_child_width + _nav_toggle_icon_width;
            /* _okayNav.getChildrenWidth(_options.parent) gets the total
               width of the <nav> element and its siblings. */


            if (visible_nav_items > 0 && nav_full_width <= collapse_width)
                _okayNav.collapseNavItem();

            if (wrapper_width > expand_width)
                _okayNav.expandNavItem();


            // Hide the kebab icon if no items are hidden
            $('li', $nav_invisible).length == 0 ? $nav_toggle_icon.hide() : $nav_toggle_icon.show();
        },

        collapseNavItem: function() {
            var $last_child = $('li:last-child', $nav_visible);
            _last_visible_child_width = $last_child.outerWidth(true);
            $last_child.detach().prependTo($nav_invisible);

            // All nav items are visible by default
            // so we only need recursion when collapsing
            _okayNav.recalcNav();
            $document.trigger('okayNav:collapseItem');
        },


        expandNavItem: function() {
            $('li:first-child', $nav_invisible).detach().appendTo($nav_visible);
            $document.trigger('okayNav:expandItem');
        },

        destroy: function() {
            $('li', $nav_invisible).appendTo($nav_visible);
            $nav_invisible.remove();
            $nav_visible.removeClass('okayNav__nav--visible');
            $nav_toggle_icon.remove();

            $document.unbind('.okayNav');
            $(window).unbind('.okayNav');
        }

    }

    // Plugin wrapper
    $.fn[okayNav] = function ( options ) {
        var args = arguments;

        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + okayNav)) {
                    $.data(this, 'plugin_' + okayNav, new Plugin( this, options ));
                }
            });

        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            var returns;
            this.each(function () {
                var instance = $.data(this, 'plugin_' + okayNav);
                if (instance instanceof Plugin && typeof instance[options] === 'function') {
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                if (options === 'destroy') {
                  $.data(this, 'plugin_' + okayNav, null);
                }
            });

            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window, document));


/*
 * @TODO: swipe gestures
*/
