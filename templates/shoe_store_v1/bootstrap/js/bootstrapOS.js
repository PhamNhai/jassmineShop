/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2017 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */


if (typeof jQuerOs === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuerOs')
}
+function ($) {
  'use strict';
  var version = $.fn.jquery.split(' ')[0].split('.')
  if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1) || (version[0] > 3)) {
    throw new Error('Bootstrap\'s JavaScript requires jQuerOs version 1.9.1 or higher, but lower than version 4')
  }
}(jQuerOs);

/* ========================================================================
 * Bootstrap: dropdown_os.js v3.3.7
 * http://getbootstrap.com/javascript/#dropdown_oss
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // DROPDOWN CLASS DEFINITION
  // =========================

  var backdrop = '.dropdown_os-backdrop'
  var toggle   = '[data-toggle="dropdown_os"]'
  var Dropdown_os = function (element) {
    $(element).on('click.bs.dropdown_os', this.toggle)
  }

  Dropdown_os.VERSION = '3.3.7'

  function getParent($this) {
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = selector && $(selector)

    return $parent && $parent.length ? $parent : $this.parent()
  }

  function clearMenus(e) {
    if (e && e.which === 3) return
    $(backdrop).remove()
    $(toggle).each(function () {
      var $this         = $(this)
      var $parent       = getParent($this)
      var relatedTarget = { relatedTarget: this }

      if (!$parent.hasClass('open')) return

      if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return

      $parent.trigger(e = $.Event('hide.bs.dropdown_os', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this.attr('aria-expanded', 'false')
      $parent.removeClass('open').trigger($.Event('hidden.bs.dropdown_os', relatedTarget))
    })
  }

  Dropdown_os.prototype.toggle = function (e) {
    var $this = $(this)

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    clearMenus()

    if (!isActive) {
      if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
        // if mobile we use a backdrop because click events don't delegate
        $(document.createElement('div'))
          .addClass('dropdown_os-backdrop')
          .insertAfter($(this))
          .on('click', clearMenus)
      }

      var relatedTarget = { relatedTarget: this }
      $parent.trigger(e = $.Event('show.bs.dropdown_os', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this
        .trigger('focus')
        .attr('aria-expanded', 'true')

      $parent
        .toggleClass('open')
        .trigger($.Event('shown.bs.dropdown_os', relatedTarget))
    }

    return false
  }

  Dropdown_os.prototype.keydown = function (e) {
    if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return

    var $this = $(this)

    e.preventDefault()
    e.stopPropagation()

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    if (!isActive && e.which != 27 || isActive && e.which == 27) {
      if (e.which == 27) $parent.find(toggle).trigger('focus')
      return $this.trigger('click')
    }

    var desc = ' li:not(.disabled):visible a'
    var $items = $parent.find('.dropdown_os-menu' + desc)

    if (!$items.length) return

    var index = $items.index(e.target)

    if (e.which == 38 && index > 0)                 index--         // up
    if (e.which == 40 && index < $items.length - 1) index++         // down
    if (!~index)                                    index = 0

    $items.eq(index).trigger('focus')
  }


  // DROPDOWN PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.dropdown_os')

      if (!data) $this.data('bs.dropdown_os', (data = new Dropdown_os(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.dropdown_os

  $.fn.dropdown_os             = Plugin
  $.fn.dropdown_os.Constructor = Dropdown_os


  // DROPDOWN NO CONFLICT
  // ====================

  $.fn.dropdown_os.noConflict = function () {
    $.fn.dropdown_os = old
    return this
  }


  // APPLY TO STANDARD DROPDOWN ELEMENTS
  // ===================================

  $(document)
    .on('click.bs.dropdown_os.data-api', clearMenus)
    .on('click.bs.dropdown_os.data-api', '.dropdown_os form', function (e) { e.stopPropagation() })
    .on('click.bs.dropdown_os.data-api', toggle, Dropdown_os.prototype.toggle)
    .on('keydown.bs.dropdown_os.data-api', toggle, Dropdown_os.prototype.keydown)
    .on('keydown.bs.dropdown_os.data-api', '.dropdown_os-menu', Dropdown_os.prototype.keydown)

}(jQuerOs);

/* ========================================================================
 * Bootstrap: tab_os.js v3.3.7
 * http://getbootstrap.com/javascript/#tab_oss
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TAB CLASS DEFINITION
  // ====================

  var Tab_os = function (element) {
    // jscs:disable requireDollarBeforejQuerOsAssignment
    this.element = $(element)
    // jscs:enable requireDollarBeforejQuerOsAssignment
  }

  Tab_os.VERSION = '3.3.7'

  Tab_os.TRANSITION_DURATION = 150

  Tab_os.prototype.show = function () {
    var $this    = this.element
    var $ul      = $this.closest('ul:not(.dropdown_os-menu)')
    var selector = $this.data('target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    if ($this.parent('li').hasClass('active')) return

    var $previous = $ul.find('.active:last a')
    var hideEvent = $.Event('hide.bs.tab_os', {
      relatedTarget: $this[0]
    })
    var showEvent = $.Event('show.bs.tab_os', {
      relatedTarget: $previous[0]
    })

    $previous.trigger(hideEvent)
    $this.trigger(showEvent)

    if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) return

    var $target = $(selector)

    this.activate($this.closest('li'), $ul)
    this.activate($target, $target.parent(), function () {
      $previous.trigger({
        type: 'hidden.bs.tab_os',
        relatedTarget: $this[0]
      })
      $this.trigger({
        type: 'shown.bs.tab_os',
        relatedTarget: $previous[0]
      })
    })
  }

  Tab_os.prototype.activate = function (element, container, callback) {
    var $active    = container.find('> .active')
    var transition = callback
      && $.support.transition
      && ($active.length && $active.hasClass('fade') || !!container.find('> .fade').length)

    function next() {
      $active
        .removeClass('active')
        .find('> .dropdown_os-menu > .active')
          .removeClass('active')
        .end()
        .find('[data-toggle="tab_os"]')
          .attr('aria-expanded', false)

      element
        .addClass('active')
        .find('[data-toggle="tab_os"]')
          .attr('aria-expanded', true)

      if (transition) {
        element[0].offsetWidth // reflow for transition
        element.addClass('in')
      } else {
        element.removeClass('fade')
      }

      if (element.parent('.dropdown_os-menu').length) {
        element
          .closest('li.dropdown_os')
            .addClass('active')
          .end()
          .find('[data-toggle="tab_os"]')
            .attr('aria-expanded', true)
      }

      callback && callback()
    }

    $active.length && transition ?
      $active
        .one('bsTransitionEnd', next)
        .emulateTransitionEnd(Tab_os.TRANSITION_DURATION) :
      next()

    $active.removeClass('in')
  }


  // TAB PLUGIN DEFINITION
  // =====================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.tab_os')

      if (!data) $this.data('bs.tab_os', (data = new Tab_os(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tab_os

  $.fn.tab_os             = Plugin
  $.fn.tab_os.Constructor = Tab_os


  // TAB NO CONFLICT
  // ===============

  $.fn.tab_os.noConflict = function () {
    $.fn.tab_os = old
    return this
  }


  // TAB DATA-API
  // ============

  var clickHandler = function (e) {
    e.preventDefault()
    Plugin.call($(this), 'show')
  }

  $(document)
    .on('click.bs.tab_os.data-api', '[data-toggle="tab_os"]', clickHandler)
    .on('click.bs.tab_os.data-api', '[data-toggle="pill"]', clickHandler)

}(jQuerOs);

/* ========================================================================
 * Bootstrap: collapse_os.js v3.3.7
 * http://getbootstrap.com/javascript/#collapse_os
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

/* jshint latedef: false */

+function ($) {
  'use strict';

  // COLLAPSE PUBLIC CLASS DEFINITION
  // ================================

  var Collapse_os = function (element, options) {
    this.$element      = $(element)
    this.options       = $.extend({}, Collapse_os.DEFAULTS, options)
    this.$trigger      = $('[data-toggle="collapse_os"][href="#' + element.id + '"],' +
                           '[data-toggle="collapse_os"][data-target="#' + element.id + '"]')
    this.transitioning = null

    if (this.options.parent) {
      this.$parent = this.getParent()
    } else {
      this.addAriaAndCollapse_osdClass(this.$element, this.$trigger)
    }

    if (this.options.toggle) this.toggle()
  }

  Collapse_os.VERSION  = '3.3.7'

  Collapse_os.TRANSITION_DURATION = 350

  Collapse_os.DEFAULTS = {
    toggle: true
  }

  Collapse_os.prototype.dimension = function () {
    var hasWidth = this.$element.hasClass('width')
    return hasWidth ? 'width' : 'height'
  }

  Collapse_os.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('in')) return

    var activesData
    var actives = this.$parent && this.$parent.children('.panel').children('.in, .collapsing')

    if (actives && actives.length) {
      activesData = actives.data('bs.collapse_os')
      if (activesData && activesData.transitioning) return
    }

    var startEvent = $.Event('show.bs.collapse_os')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    if (actives && actives.length) {
      Plugin.call(actives, 'hide')
      activesData || actives.data('bs.collapse_os', null)
    }

    var dimension = this.dimension()

    this.$element
      .removeClass('collapse_os')
      .addClass('collapsing')[dimension](0)
      .attr('aria-expanded', true)

    this.$trigger
      .removeClass('collapse_osd')
      .attr('aria-expanded', true)

    this.transitioning = 1

    var complete = function () {
      this.$element
        .removeClass('collapsing')
        .addClass('collapse_os in')[dimension]('')
      this.transitioning = 0
      this.$element
        .trigger('shown.bs.collapse_os')
    }

    if (!$.support.transition) return complete.call(this)

    var scrollSize = $.camelCase(['scroll', dimension].join('-'))

    this.$element
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse_os.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])
  }

  Collapse_os.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('in')) return

    var startEvent = $.Event('hide.bs.collapse_os')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var dimension = this.dimension()

    this.$element[dimension](this.$element[dimension]())[0].offsetHeight

    this.$element
      .addClass('collapsing')
      .removeClass('collapse_os in')
      .attr('aria-expanded', false)

    this.$trigger
      .addClass('collapse_osd')
      .attr('aria-expanded', false)

    this.transitioning = 1

    var complete = function () {
      this.transitioning = 0
      this.$element
        .removeClass('collapsing')
        .addClass('collapse_os')
        .trigger('hidden.bs.collapse_os')
    }

    if (!$.support.transition) return complete.call(this)

    this.$element
      [dimension](0)
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse_os.TRANSITION_DURATION)
  }

  Collapse_os.prototype.toggle = function () {
    this[this.$element.hasClass('in') ? 'hide' : 'show']()
  }

  Collapse_os.prototype.getParent = function () {
    return $(this.options.parent)
      .find('[data-toggle="collapse_os"][data-parent="' + this.options.parent + '"]')
      .each($.proxy(function (i, element) {
        var $element = $(element)
        this.addAriaAndCollapse_osdClass(getTargetFromTrigger($element), $element)
      }, this))
      .end()
  }

  Collapse_os.prototype.addAriaAndCollapse_osdClass = function ($element, $trigger) {
    var isOpen = $element.hasClass('in')

    $element.attr('aria-expanded', isOpen)
    $trigger
      .toggleClass('collapse_osd', !isOpen)
      .attr('aria-expanded', isOpen)
  }

  function getTargetFromTrigger($trigger) {
    var href
    var target = $trigger.attr('data-target')
      || (href = $trigger.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') // strip for ie7

    return $(target)
  }


  // COLLAPSE PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.collapse_os')
      var options = $.extend({}, Collapse_os.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data && options.toggle && /show|hide/.test(option)) options.toggle = false
      if (!data) $this.data('bs.collapse_os', (data = new Collapse_os(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.collapse_os

  $.fn.collapse_os             = Plugin
  $.fn.collapse_os.Constructor = Collapse_os


  // COLLAPSE NO CONFLICT
  // ====================

  $.fn.collapse_os.noConflict = function () {
    $.fn.collapse_os = old
    return this
  }


  // COLLAPSE DATA-API
  // =================

  $(document).on('click.bs.collapse_os.data-api', '[data-toggle="collapse_os"]', function (e) {
    var $this   = $(this)

    if (!$this.attr('data-target')) e.preventDefault()

    var $target = getTargetFromTrigger($this)
    var data    = $target.data('bs.collapse_os')
    var option  = data ? 'toggle' : $this.data()

    Plugin.call($target, option)
  })

}(jQuerOs);

/* ========================================================================
 * Bootstrap: transition.js v3.3.7
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      WebkitTransition : 'webkitTransitionEnd',
      MozTransition    : 'transitionend',
      OTransition      : 'oTransitionEnd otransitionend',
      transition       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }

    return false // explicit for ie8 (  ._.)
  }

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false
    var $el = this
    $(this).one('bsTransitionEnd', function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()

    if (!$.support.transition) return

    $.event.special.bsTransitionEnd = {
      bindType: $.support.transition.end,
      delegateType: $.support.transition.end,
      handle: function (e) {
        if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
      }
    }
  })

}(jQuerOs);
