/*
 * jQuery Navgoco Menus Plugin v0.2.1 (2014-04-11)
 * https://github.com/tefra/navgoco
 *
 * Copyright (c) 2014 Chris T (@tefra)
 * BSD - https://github.com/tefra/navgoco/blob/master/LICENSE-BSD
 *
 *
 * CUSTOM VERSION FOR CI MEMBERSHIP 3.x.x: many changes were made to the original file to make sure the menu
 * works with the narrow dropout.
 *
 *
 */
(function($) {

	"use strict";

	/**
	 * Plugin Constructor. Every menu must have a unique id which will either
	 * be the actual id attribute or its index in the page.
	 *
	 * @param {Element} el
	 * @param {Object} options
	 * @param {Integer} idx
	 * @returns {Object} Plugin Instance
	 */
	var Plugin = function(el, options, idx) {
		this.el = el;
		this.$el = $(this.el);
		this.options = options;
		this.uuid = this.$el.attr('id') ? this.$el.attr('id') : idx;
		this.state = {};
		this.init();
		return this;
	};

	/**
	 * Plugin methods
	 */
	Plugin.prototype = {
		/**
		 * Load cookie, assign a unique data-index attribute to
		 * all sub-menus and show|hide them according to cookie
		 * or based on the parent open class. Find all parent li > a
		 * links add the caret if it's on and attach the event click
		 * to them.
		 */
		init: function() {
			var self = this;
			self._load();
			self.$el.find('ul').each(function(idx) {
				var sub = $(this);
				sub.attr('data-index', idx);
				if (self.options.save && self.state.hasOwnProperty(idx)) {
					sub.parent().addClass(self.options.openClass);
					sub.show();
				} else if (sub.parent().hasClass(self.options.openClass)) {
					sub.show();
					self.state[idx] = 1;
				} else {
					sub.hide();
				}
			});

            // custom caret with configurable icons
            var caret = $('<span class="menu-caret ' + self.options.caretClassCollapsed + '"></span>').prepend(self.options.caretHtml);
            // separate triggers for top links and sub links
            var topLinks = self.$el.find("> li > a");
            var links = self.$el.find("> li > ul li > a");
            self._trigger(links, true, "click");
            if ($(self.options.wrapperDiv).hasClass('aside-narrow')) {
                self._trigger(topLinks, true, "mouseenter");
            }else{
                self._trigger(topLinks, true, "click");
            }

            self.$el.find("li:has(ul) > a").prepend(caret); // add caret to each list item with subs

            /*$(".aside-narrow #demo1 > li > ul").on('mouseenter', function(){
                if (!$(this).parent().hasClass('open')) {
                    $(this).parent().addClass('open');
                }
            });

            $(".aside-narrow #demo1 > li > ul").on('mouseleave', function(){
                if ($(this).parent().hasClass('open')) {
                    $(this).parent().removeClass('open');
                }
            });*/
		},
		/**
		 * Add the main event trigger to toggle menu items to the given sources
		 * @param {Element} sources
		 * @param {Boolean} isLink
         * @param {String} myEvent
		 */
		_trigger: function(sources, isLink, myEvent) {

			var self = this;
            var currentEvent = "click";

            // when we have set a custom event, use that one instead: it has the highest importance
            if (typeof myEvent !== "undefined"  && myEvent.length > 0) {
                currentEvent = myEvent;
            }

            // add the events to our elements
			sources.on(currentEvent + '.navgoco', function(event) { // navgoco event namespace
                //console.log('event triggered!');
				event.stopPropagation();
				var sub = isLink ? $(this).next() : $(this).parent().next();

				var isAnchor = false;
				if (isLink) {
					var href = $(this).attr('href');
					isAnchor = href === undefined || href === '' || href === '#' || href === 'javascript:'; // added javascript to avoid having to use anchors in HTML
				}
				sub = sub.length > 0 ? sub : false;
				self.options.onClickBefore.call(this, event, sub);

				if (!isLink || sub && isAnchor) {
					event.preventDefault();
                    self._toggle(sub, sub.is(":hidden"));

                    // uncomment the below when you want narrow submenus to close when hovering over other menu links.
                    /*if (currentEvent == "mouseenter") { // CHANGED - collapse all submenus when changing menus to avoid the open sub menus all over the place
                        self.toggle(); // narrow menu titles also close the open submenu on hover
                    }*/

					self._save();

				} else if (self.options.accordion) {
					var allowed = self.state = self._parents($(this));
					self.$el.find('ul').filter(':visible').each(function() {
						var sub = $(this),
							idx = sub.attr('data-index');

						if (!allowed.hasOwnProperty(idx)) {
							self._toggle(sub, false);
						}
					});

					self._save();
				}
				self.options.onClickAfter.call(this, event, sub);
			});
		},
		/**
		 * Accepts a JQuery Element and a boolean flag. If flag is false it removes the `open` css
		 * class from the parent li and slides up the sub-menu. If flag is open it adds the `open`
		 * css class to the parent li and slides down the menu. If accordion mode is on all
		 * sub-menus except the direct parent tree will close. Internally an object with the menus
		 * states is maintained for later save duty.
		 *
		 * @param {Element} sub
		 * @param {Boolean} open
		 */
		_toggle: function(sub, open) {
			var self = this,
				idx = sub.attr('data-index'),
				parent = sub.parent();



			self.options.onToggleBefore.call(this, sub, open);

            // check whether there is is link title or not and set slide duration to 0 when found (animations on link titles (top links) must be disabled)
            if (sub.prev().find('> span.menu-link-title').length > 0 && $(self.options.wrapperDiv).hasClass('aside-narrow')) {
                self.options.slide.duration = 0;
            }else{
                self.options.slide.duration = 200;
            }

			if (open) {
                //console.log('open');
				parent.addClass(self.options.openClass);
				sub.slideDown(self.options.slide);
				self.state[idx] = 1;

                // change icon
                parent.find('.menu-caret').first().removeClass(self.options.caretClassCollapsed).addClass(self.options.caretClassExpanded);

				if (self.options.accordion) {
					var allowed = self.state = self._parents(sub);
					allowed[idx] = self.state[idx] = 1;

					self.$el.find('ul').filter(':visible').each(function() {
						var sub = $(this),
							idx = sub.attr('data-index');

						if (!allowed.hasOwnProperty(idx)) {
							self._toggle(sub, false);
						}
					});
				}
			} else {
                //console.log('close');
				parent.removeClass(self.options.openClass);
				sub.slideUp(self.options.slide);
				self.state[idx] = 0;

                // change icon
                parent.find('.menu-caret').first().removeClass(self.options.caretClassExpanded).addClass(self.options.caretClassCollapsed);
			}
			self.options.onToggleAfter.call(this, sub, open);
		},
		/**
		 * Returns all parents of a sub-menu. When obj is true It returns an object with indexes for
		 * keys and the elements as values, if obj is false the object is filled with the value `1`.
		 *
		 * @since v0.1.2
		 * @param {Element} sub
		 * @param {Boolean} obj
		 * @returns {Object}
		 */
		_parents: function(sub, obj) {
			var result = {},
				parent = sub.parent(),
				parents = parent.parents('ul');

			parents.each(function() {
				var par = $(this),
					idx = par.attr('data-index');

				if (!idx) {
					return false;
				}
				result[idx] = obj ? par : 1;
			});
			return result;
		},
		/**
		 * If `save` option is on the internal object that keeps track of the sub-menus states is
		 * saved with a cookie. For size reasons only the open sub-menus indexes are stored.		 *
		 */
		_save: function() {
			if (this.options.save) {
				var save = {};
				for (var key in this.state) {
					if (this.state[key] === 1) {
						save[key] = 1;
					}
				}
				cookie[this.uuid] = this.state = save;
				$.cookie(this.options.cookie.name, JSON.stringify(cookie), this.options.cookie);
			}
		},
		/**
		 * If `save` option is on it reads the cookie data. The cookie contains data for all
		 * navgoco menus so the read happens only once and stored in the global `cookie` var.
		 */
		_load: function() {
			if (this.options.save) {
				if (cookie === null) {
					var data = $.cookie(this.options.cookie.name);
					cookie = (data) ? JSON.parse(data) : {};
				}
				this.state = cookie.hasOwnProperty(this.uuid) ? cookie[this.uuid] : {};
			}
		},
		/**
		 * Public method toggle to manually show|hide sub-menus. If no indexes are provided all
		 * items will be toggled. You can pass sub-menus indexes as regular params. eg:
		 * navgoco('toggle', true, 1, 2, 3, 4, 5);
		 *
		 * Since v0.1.2 it will also open parents when providing sub-menu indexes.
		 *
		 * @param {Boolean} open
		 */
		toggle: function(open) {
			var self = this,
				length = arguments.length;

			if (length <= 1) {
				self.$el.find('ul').each(function() {
					var sub = $(this);
					self._toggle(sub, open);
				});
			} else {
				var idx,
					list = {},
					args = Array.prototype.slice.call(arguments, 1);
				length--;

				for (var i = 0; i < length; i++) {
					idx = args[i];
					var sub = self.$el.find('ul[data-index="' + idx + '"]').first();
					if (sub) {
						list[idx] = sub;
						if (open) {
							var parents = self._parents(sub, true);
							for (var pIdx in parents) {
								if (!list.hasOwnProperty(pIdx)) {
									list[pIdx] = parents[pIdx];
								}
							}
						}
					}
				}

				for (idx in list) {
					self._toggle(list[idx], open);
				}
			}
			self._save();
		},

        /* custom function to deal with easing effects: this function allows to change easing on the fly */
        duration: function(ms) {
            var self = this;
            self.options.slide = ms;
        },
		/**
		 * CHANGED - Completely custom function
		 */
		reset: function() {
            var self = this;

            // destroy everything I can find now
			$.removeData(this.$el);
            this.$el.find("li a").unbind("click.navgoco");
            this.$el.find("li a").unbind("mouseenter.navgoco");
            this.$el.find("li a").off("click.navgoco");
            this.$el.find("li a").off("mouseenter.navgoco");
            this.$el.find("li a > .menu-caret").remove();

            // reinit plugin
            self.init();

            /*$(".aside-narrow #demo1 > li > ul").on('mouseenter', function(){
                if (!$(this).parent().hasClass('open')) {
                    $(this).parent().addClass('open');
                }
            });

            $(".aside-narrow #demo1 > li > ul").on('mouseleave', function(){
                if ($(this).parent().hasClass('open')) {
                    $(this).parent().removeClass('open');
                }
            });*/

		}
	};

	/**
	 * A JQuery plugin wrapper for navgoco. It prevents from multiple instances and also handles
	 * public methods calls. If we attempt to call a public method on an element that doesn't have
	 * a navgoco instance, one will be created for it with the default options.
	 *
	 * @param {Object|String} options
	 */
	$.fn.navgoco = function(options) {
		if (typeof options === 'string' && options.charAt(0) !== '_' && options !== 'init') {
			var callback = true,
				args = Array.prototype.slice.call(arguments, 1);
		} else {
			options = $.extend({}, $.fn.navgoco.defaults, options || {});
			if (!$.cookie) {
				options.save = false;
			}
		}
		return this.each(function(idx) {
			var $this = $(this),
				obj = $this.data('navgoco');

			if (!obj) {
				obj = new Plugin(this, callback ? $.fn.navgoco.defaults : options, idx);
				$this.data('navgoco', obj);
			}
			if (callback) {
				obj[options].apply(obj, args);
			}
		});
	};
	/**
	 * Global var holding all navgoco menus open states
	 *
	 * @type {Object}
	 */
	var cookie = null;

	/**
	 * Default navgoco options
	 *
	 * @type {Object}
	 */
	$.fn.navgoco.defaults = {
        caretHtml: '',
        caretClassCollapsed: 'glyphicon glyphicon-menu-down',
        caretClassExpanded: 'glyphicon glyphicon-menu-up',
        wrapperDiv: '.wrap',
		accordion: false,
		openClass: 'open',
		save: true,
		cookie: {
			name: 'navgoco',
			expires: false,
			path: '/'
		},
		slide: {
			duration: 200,
			easing: 'swing'
		},
		onClickBefore: $.noop,
		onClickAfter: $.noop,
		onToggleBefore: $.noop,
		onToggleAfter: $.noop
	};
})(jQuery);