!function($) {
    var Slider = function(element, options) {
        this.element = $(element);
        this.picker = $('<div class="slider"><div class="slider-track">'+
                        '<div class="slider-selection"></div>'+
                        '<div class="slider-handle"></div>'+
                        '<div class="slider-handle"></div></div><div class="ba-tooltip">'+
                        '</div></div>').insertBefore(this.element).append(this.element);
        this.id = this.element.data('slider-id')||options.id;
        if (this.id) {
            this.picker[0].id = this.id;
        }
        if (typeof Modernizr !== 'undefined' && Modernizr.touch) {
            this.touchCapable = true;
        }
        this.tooltip = this.picker.find('.ba-tooltip');
        this.picker.addClass('slider-horizontal');
        this.stylePos = 'left';
        this.mousePos = 'pageX';
        this.sizePos = 'offsetWidth';
        this.min = this.element.data('slider-min')||options.min;
        this.max = this.element.data('slider-max')||options.max;
        this.step = this.element.data('slider-step')||options.step;
        this.value = this.element.data('slider-value')||options.value;
        this.selectionEl = this.picker.find('.slider-selection');
        this.selectionElStyle = this.selectionEl[0].style;
        this.handle1 = this.picker.find('.slider-handle:first');
        this.handle1Stype = this.handle1[0].style;
        this.handle2 = this.picker.find('.slider-handle:last');
        this.handle2Stype = this.handle2[0].style;
        this.handle1.addClass('round');
        this.handle2.addClass('round');
        this.value[0] = Math.max(this.min, Math.min(this.max, this.value[0]));
        this.value[1] = Math.max(this.min, Math.min(this.max, this.value[1]));
        this.diff = this.max - this.min;
        this.percentage = [
            (this.value[0]-this.min)*100/this.diff,
            (this.value[1]-this.min)*100/this.diff,
            this.step*100/this.diff
        ];
        this.offset = this.picker.offset();
        this.size = this.picker[0][this.sizePos];
        this.formater = options.formater;
        this.layout();
        if (this.touchCapable) {
            this.picker.on({
                touchstart: $.proxy(this.mousedown, this)
            });
        } else {
            var $this = this;
            this.picker.on('mouseenter', function(){
                $this.size = $this.picker[0][$this.sizePos];
                $this.tooltip[0].style[$this.stylePos] = $this.size *($this.percentage[0]+($this.percentage[1]-$this.percentage[0])/2)/100-$this.tooltip.outerWidth()/2+'px';
            })
            this.picker.on({
                mousedown: $.proxy(this.mousedown, this)
            });
        }        
    };    
    Slider.prototype = {
        constructor: Slider,
        over: false,
        inDrag: false,
        layout: function(){
            var baSlider = this.picker.parent().find('.ba-slider-values');
            this.handle1Stype[this.stylePos] = this.percentage[0]+'%';
            this.handle2Stype[this.stylePos] = this.percentage[1]+'%';
            this.selectionElStyle.left = Math.min(this.percentage[0], this.percentage[1]) +'%';
            this.selectionElStyle.width = Math.abs(this.percentage[0] - this.percentage[1]) +'%';
            this.tooltip.text(
                this.formater(Math.round(this.value[0]))+' - '+this.formater(Math.round(this.value[1]))
            );
            baSlider.val(this.formater(Math.round(this.value[0]))+' — '+this.formater(Math.round(this.value[1])));
            this.tooltip[0].style[this.stylePos] = this.size *(this.percentage[0]+(this.percentage[1]-this.percentage[0])/2)/100-this.tooltip.outerWidth()/2+'px';
		},
        mousedown: function(ev) {
            if (this.touchCapable && ev.type === 'touchstart') {
                ev = ev.originalEvent;
            }
            this.offset = this.picker.offset();
            this.size = this.picker[0][this.sizePos];
            var percentage = this.getPercentage(ev);
            var diff1 = Math.abs(this.percentage[0] - percentage);
            var diff2 = Math.abs(this.percentage[1] - percentage);
            this.dragged = (diff1 < diff2) ? 0 : 1;
            this.percentage[this.dragged] = percentage;
            this.layout();
            if (this.touchCapable) {
                $(document).on({
                    touchmove: $.proxy(this.mousemove, this),
                    touchend: $.proxy(this.mouseup, this)
                });
            } else {
                $(document).on({
                    mousemove: $.proxy(this.mousemove, this),
                    mouseup: $.proxy(this.mouseup, this)
                });
            }
            this.inDrag = true;
            var val = this.calculateValue();
            this.element.trigger({
                type: 'slideStart',
                value: val
            }).trigger({
                type: 'slide',
                value: val
            });
            return false;
        },
        mousemove: function(ev) {
            if (this.touchCapable && ev.type === 'touchmove') {
                ev = ev.originalEvent;
            }
            var percentage = this.getPercentage(ev);
            if (this.dragged === 0 && this.percentage[1] < percentage) {
                this.percentage[0] = this.percentage[1];
                this.dragged = 1;
            } else if (this.dragged === 1 && this.percentage[0] > percentage) {
                this.percentage[1] = this.percentage[0];
                this.dragged = 0;
            }
            this.percentage[this.dragged] = percentage;
            this.layout();
			var val = this.calculateValue();
			this.element
				.trigger({
					type: 'slide',
					value: val
				})
				.data('value', val)
				.prop('value', val);
			return false;
		},
        mouseup: function(ev) {
            if (this.touchCapable) {
                $(document).off({
					touchmove: this.mousemove,
					touchend: this.mouseup
				});
			} else {
				$(document).off({
					mousemove: this.mousemove,
					mouseup: this.mouseup
				});
			}
            this.inDrag = false;
			this.element;
            this.layout();
			var val = this.calculateValue();
            this.element
				.trigger({
					type: 'slideStop',
					value: val
				})
				.data('value', val)
				.prop('value', val);
			return false;
		},
        calculateValue: function() {
			var val;
			val = [
                ((this.percentage[0]*1/this.percentage[2]*1)*this.step+this.min*1),
                ((this.percentage[1]*1/this.percentage[2]*1)*this.step+this.min*1)
            ];
            this.value = val;
			return val;
		},
        getPercentage: function(ev) {
			if (this.touchCapable) {
				ev = ev.touches[0];
			}
			var percentage = (ev[this.mousePos] - this.offset[this.stylePos])*100/this.size;
			percentage = Math.round(percentage/this.percentage[2])*this.percentage[2];
			return Math.max(0, Math.min(100, percentage));
		}
	};
    $.fn.slider = function ( option, val ) {
		return this.each(function () {
			var $this = $(this),
				data = $this.data('slider'),
				options = typeof option === 'object' && option;
			if (!data)  {
				$this.data('slider', (data = new Slider(this, $.extend({}, $.fn.slider.defaults,options))));
			}
			if (typeof option == 'string') {
				data[option](val);
			}
		})
	};

	$.fn.slider.defaults = {
		min: 0,
		max: 10,
		step: 1,
		value: 5,
		formater: function(value) {
			return value;
		}
	};

	$.fn.slider.Constructor = Slider;

}(window.jQuery);