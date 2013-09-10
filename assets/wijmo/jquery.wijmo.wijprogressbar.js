/*globals setTimeout jQuery*/

/*
*
* Wijmo Library 1.2.0
* http://wijmo.com/
*
* Copyright(c) ComponentOne, LLC.  All rights reserved.
* 
* Dual licensed under the MIT or GPL Version 2 licenses.
* licensing@wijmo.com
* http://www.wijmo.com/license
*
** wijprogressbar Widget. V1.0
*
* Copyright (c) Componentone Inc.
*
* Depends:
*	Jquery-1.4.2.js
*	jquery.ui.core.js
*	jquery.ui.widget.js
*
*Optional dependence for effect settings:
*	jquery.effects.core.js
*	jquery.effects.blind.js
*	jquery.effects.bounce.js
*	jquery.effects.clip.js
*	jquery.effects.drop.js
*	jquery.effects.explode.js
*	jquery.effects.fold.js
*	jquery.effects.hightlight.js
*	jquery.effects.pulsate.js
*	jquery.effects.scale.js
*	jquery.effects.shake.js
*	jquery.effects.slide.js
*	jquery.effects.transfer.js
* HTML:
*  <div id="progressbar" style="width:***;height:***"></div>
*/
(function ($) {
	"use strict";
	var wijpbCss = "wijmo-wijprogressbar",
		pbCss = "ui-progressbar",
		pbLabelCss = pbCss + "-label",
		lblAlignPrefixCss = wijpbCss + "-lb-",
		cornerPrefixCss = "ui-corner-",
		cornerLeftCss = cornerPrefixCss + "left",
		cornerRightCss = cornerPrefixCss + "right",
		cornerTopCss = cornerPrefixCss + "top",
		cornerBottomCss = cornerPrefixCss + "bottom";
	$.widget("wijmo.wijprogressbar", $.ui.progressbar, {
		options: {
			/// <summary>
			///The label's alignment on the progress bar. The value should be "east",
			/// "west", "center", "north", "south" or "running".
			///Default:"center".
			///Type:String.
			///Code sample:$('.selector').wijprogressbar('option','labelAlign','center').
			///</summary>
			labelAlign: "center",
			/// <summary>
			///The value of the progress bar,the type should be numeric.
			///Default:0.
			///Type:Number.
			///Code sample:$('.selector').wijprogressbar('option','value',60).
			///</summary>
			maxValue: 100,
			/// <summary>
			///The minimum value of the progress bar,the type should be numeric.
			///Default:0.
			///Type:Number.
			///Code sample:$('.selector').wijprogressbar('option','minValue',0).
			///</summary>
			minValue: 0,
			/// <summary>
			///The fill direction of the progress bar.the value should be "east", 
			///"west", "north" or "south".
			///Default:"east".
			///Type:String.
			///Code sample:$('.selector').wijprogressbar('option','fillDirection','east').
			///</summary>
			fillDirection: "east",
			/// <summary>
			///The progressbar's orientation.the value should be 'horizontal'
			/// or 'vertical'.
			///Default:"horizontal".
			///Type:String.
			///Code sample:$('selector').wijprogressbar('option','orientation',
			///'horizontal').
			///</summary>
			///orientation: "horizontal",
			/// <summary>
			///Sets the format of the label text.The available formats are as follows:
			///{0} or {ProgressValue} express the current progress Value.
			///{1} or {PercentProgress} express the current percent of the progress bar.
			///{2} or {RemainingProgress} express the remaining progress of the 
			///progress bar.
			///{3} or {PercentageRemaining} express the remaining percent of 
			///the progress bar.
			///{4} or {Min} express the min Vlaue of the progress bar.
			///{5} or {Max} express the max Value of the progress bar.
			///Default:"{1}%".
			///Type:String.
			///Code sample:$('.selector').wijprogressbar('option','labelFormatString'
			///,'{0}%').
			///</summary>
			labelFormatString: "{1}%",
			/// <summary>
			///Set the format of the ToolTip of the progress bar,the expression of the 
			///format like the labelFormatString.
			///Default:"{1}%".
			///Type:String.
			///Code sample:$('.selector').wijprogressbar('option','toolTipFormatString'
			///,'{1}%').
			///</summary>
			toolTipFormatString: "{1}%",
			/// <summary>
			///The increment of the progress bar's indicator.
			///Default:1.
			///Type:Number.
			///</summary>
			///Code sample:$('.selector').wijprogressbar('option',
			///'indicatorIncrement',10).
			indicatorIncrement: 1,
			/// <summary>
			///The Image's url of the indicator.
			///Default:"".
			///Type:String.
			///Code sample:$('.selector').wijprogressbar('option','indicatorImage',
			///'images/abc.png').
			///</summary>
			indicatorImage: "",
			/// <summary>
			///The delay of the progressbar's animation.
			///Default:0.
			///Type:Number.
			///Code sample:$('.selector').wijprogressbar('option',
			///</summary>
			animationDelay: 0,
			/// <summary>
			///The options parameter of the jQuery's animation.
			///Default:"{animated:'progress', duration:500, disabled:false}".
			///Type:Options.
			///Code sample:$('.selector').wijprogressbar('option','animationOptions',
			///{animated:'progress',duration:600}).
			///</summary>
			animationOptions: {
				disabled: false,
				easing: null,
				duration: 500
			},
			/// <summary>
			/// Fire upon running the progress.
			/// Default: null.
			/// Type: Function
			/// Code example: $(".selector").wijprogressbar("progressChanging", 
			/// function(e, data){})
			/// </summary>
			/// <param name="e" type="Object">
			/// jQuery.Event object
			/// </param>
			/// <param name="data" type="Object">
			/// data.oldValue: The old value of the progressbar.
			/// data.newValue: The new value of the progressbar.
			///</param>
			/// <returns type="Boolean" >
			/// return false to cancel the event.
			/// </returns>
			progressChanging: null,
			/// <summary>
			/// Fires before running the progress.
			/// Default: null.
			/// Type: Function
			/// Code example: $(".selector").wijprogressbar("beforeProgressChanging",
			/// function(e, data){})
			/// </summary>
			/// <param name="e" type="Object">
			/// jQuery.Event object.
			/// </param>
			/// <param name="data" type="Object">
			/// data.oldValue: The old value of the progressbar.
			/// data.newValue: The new value of the progressbar.
			/// </param>
			/// <returns type="Boolean">
			/// Return false to cancel the event.
			/// </returns>
			beforeProgressChanging: null,
			/// <summary>
			/// Fires when the progress changes.
			/// Default: null.
			/// Type: Function
			/// Code example: $(".selector").wijprogressbar("progressChanged", 
			/// function(e, data){})
			/// </summary>
			/// <param name="e" type="Object">
			/// jQuery.Event object.
			/// </param>
			/// <param name="data" type="Object">
			/// data.oldValue: The old value of the progressbar.
			/// data.newValue: The new value of the progressbar.
			/// </param>
			progressChanged: null
		},

		_setOption: function (key, value) {
			var self = this,
				o = self.options,
				val;

			switch (key) {
				case "value":
					o[key] = parseInt(value, 10);
					self._refreshValue();
					return;
				case "maxValue":
				case "minValue":
					val = parseInt(value, 10);
					o[key] = val;
					self[key === "maxValue" ? "max" : "min"] = val;
					self._refreshValue();
					return;
				case "labelFormatString":
				case "toolTipFormatString":
					o[key] = value;
					self._refreshValue();
					return;
				case "fillDirection":
				case "labelAlign":
				case "indicatorImage":
					o[key] = value;
					self._updateElementsCss();
					return;
				default:
					break;
			}

			$.Widget.prototype._setOption.apply(self, arguments);
		},

		_create: function () {
			var self = this,
				o = self.options,
				element = self.element;

			self.directions = { east: "left", west: "right",
				north: "bottom", south: "top"
			};
			self.min = o.minValue;
			self.max = o.maxValue;
			element.addClass(wijpbCss);
			$.ui.progressbar.prototype._create.apply(self, arguments);
			self.label = $("<span>")
				.addClass(pbLabelCss).appendTo(element);
			self._updateElementsCss();
			self._isInit = true;
			self._refreshValue();
		},

		_triggerEvent: function (eventName, oldValue, newValue) {
			return this._trigger(eventName, null, {
				oldValue: oldValue,
				newValue: newValue
			}) === false;
		},

		_refreshValue: function () {
			var self = this,
				o = self.options,
				animationOptions = o.animationOptions,
				element = self.element,
				value, percent, oldValue;

			if (!self._isInit) {
				return;
			}

			oldValue = element.attr("aria-valuenow");
			value = self.value();
			percent = (value - self.min) / (self.max - self.min) * 100;

			if (self._triggerEvent("beforeProgressChanging",
				oldValue, value)) {
				return;
			}

			if (!animationOptions.disabled && animationOptions.duration > 0) {
				setTimeout(function () {
					$.wijmo.wijprogressbar.animations.progress({
						content: self.valueDiv,
						complete: function () {
							self._triggerEvent("progressChanged",
								oldValue, value);
						},
						step: function (ovalue) {
							self._performAnimating(ovalue);
						},
						progressValue: percent
					}, animationOptions);
				}, o.animationDelay);
			} else {
				self._refreshProgress(Math.round(percent));
				self._lastStep = percent;
				self._triggerEvent("progressChanged", oldValue, value);
			}
		},

		_refreshProgress: function (percent) {
			var self = this,
				o = self.options,
				fillDirection = o.fillDirection,
				element = self.element,
				curValue = percent * (self.max - self.min) / 100 + self.min;

			if (self._triggerEvent("progressChanging",
				element.attr("aria-valuenow"), curValue)) {
				return;
			}

			if (self._isHorizontal()) {
				self.valueDiv.toggleClass(fillDirection === "east" ?
					cornerRightCss : cornerLeftCss, curValue === self.max)
				.width(percent + "%");
			} else {
				self.valueDiv.toggleClass(fillDirection === "south" ?
					cornerBottomCss : cornerTopCss, curValue === self.max)
				.height(percent + "%");
			}

			self.label.html(self._getFormatString(
				o.labelFormatString, percent, curValue));

			element.attr("aria-valuenow", curValue)
				.attr("title", self._getFormatString(
				o.toolTipFormatString, percent, curValue));
		},

		_isHorizontal: function () {
			var fillDirection = this.options.fillDirection;

			return fillDirection === "west" ||
				fillDirection === "east";
		},

		_getRotateTextOffset: function (label) {
			var width, height;

			label.css("width", "auto");

			width = label.outerWidth();
			height = label.outerHeight();

			label.css("width", "");

			return Math.max(width - height - 4, 0);
		},

		_updateElementsCss: function () {
			var self = this,
				o = self.options,
				element = self.element,
				fillDirection = o.fillDirection;

			element.removeClass(wijpbCss + "-west " +
				wijpbCss + "-east " + wijpbCss + "-north " +
				wijpbCss + "-south")
			.addClass(wijpbCss + "-" + fillDirection);

			//pb progress
			self._updateProgressCss();

			//pb label
			self._updateLabelCss();
		},

		_updateLabelCss: function () {
			var self = this,
				o = self.options,
				element = self.element,
				labelAlign = o.labelAlign,
				label = self.label,
				lastStep = self._lastStep,
				height = element.height();

			label.removeClass(lblAlignPrefixCss + "west " +
				lblAlignPrefixCss + "east " +
				lblAlignPrefixCss + "south " +
				lblAlignPrefixCss + "north " +
				lblAlignPrefixCss + "center " +
				lblAlignPrefixCss + "running")
			.addClass(lblAlignPrefixCss + labelAlign)
			.css({
				left: "",
				right: "",
				top: "",
				bottom: "",
				width: "",
				"text-align": "",
				"line-height": ""
			});

			if (labelAlign !== "north" && labelAlign !== "south" &&
				!(labelAlign === "running" && !self._isHorizontal())) {
				label.css("line-height", height + "px");
			}

			if (labelAlign === "running") {
				self._updateRunningLabelCss(lastStep);
			} else if (!self._isHorizontal() && !$.browser.msie) {
				//Add comments by RyanWu@20110325.
				//Because nonIE brownser will rotate the text,
				//I use a hack to set the text-align:right and 
				//width:100%, then rotate it to the north.
				if (labelAlign === "north") {
					label.css("width", "100%")
						.css("line-height", height + "px")
						.css("text-align", "right");
				} else if (labelAlign === "south") {
					label.css("width", "100%")
						.css("line-height", height + "px")
						.css("text-align", "left");
				}
				//end by RyanWu@20110325.
			}
		},

		_updateRunningLabelCss: function (step) {
			var self = this,
				o = self.options,
				fillDirection = o.fillDirection,
				element = self.element,
				label = self.label,
				valueDiv = self.valueDiv,
				isHorizontal = self._isHorizontal(),
				pbLen, lblLen, pgLen, pos;

			pbLen = element[isHorizontal ? "width" : "height"]();
			lblLen = label[isHorizontal ? "outerWidth" : "outerHeight"]();
			pgLen = valueDiv[isHorizontal ? "outerWidth" : "outerHeight"]();

			//TODO:
			//Because the text will be rotated, we need calculate 
			//the correct lengh of the rotated text.
			if (!isHorizontal && !$.browser.msie) {
				lblLen += self._getRotateTextOffset(label);
			}

			pos = pbLen === pgLen ? pbLen - lblLen :
						step * pbLen / 100 - lblLen + lblLen *
						(pbLen - pgLen) / pbLen;

			label.css(self.directions[fillDirection], pos);
		},

		_updateProgressCss: function () {
			var self = this,
				o = self.options,
				fillDirection = o.fillDirection,
				indicatorImage = o.indicatorImage,
				valueDiv = self.valueDiv,
				lastStep = self._lastStep;

			if (indicatorImage !== "") {
				valueDiv.css("background", "transparent url(" +
					indicatorImage + ") repeat fixed");
			}

			valueDiv.removeClass(cornerLeftCss + " " +
				cornerRightCss + " " + cornerTopCss + " " +
				cornerBottomCss)
			.addClass(cornerPrefixCss + self.directions[fillDirection]);

			if (lastStep) {
				if (self._isHorizontal()) {
					valueDiv.css("width", lastStep + "%")
					.css("height", "");
				} else {
					valueDiv.css("height", lastStep + "%")
					.css("width", "");
				}
			} else {
				valueDiv.css({
					width: "",
					height: ""
				});
			}
		},

		_performAnimating: function (step) {
			var self = this,
				o = self.options,
				indicatorIncrement = o.indicatorIncrement;

			if (indicatorIncrement !== 1) {
				step = Math.floor(step / indicatorIncrement) * indicatorIncrement;
			} else {
				step = Math.round(step);
			}

			if (self._lastStep === step) {
				return;
			}

			self._lastStep = step;
			self._refreshProgress(step);

			if (o.labelAlign === "running") {
				self._updateRunningLabelCss(step);
			}
		},

		_getFormatString: function (format, percent, value) {
			var self = this,
			remainingProgress = self.max - value,
			percentageRemaining = 100 - percent,
			r = /\{0\}/g;

			format = format.replace(r, value.toString());

			r = /\{ProgressValue\}/g;
			format = format.replace(r, value.toString());

			r = /\{1\}/g;
			format = format.replace(r, percent.toString());

			r = /\{PercentProgress\}/g;
			format = format.replace(r, percent.toString());

			r = /\{2\}/g;
			format = format.replace(r, remainingProgress.toString());

			r = /\{RemainingProgress\}/g;
			format = format.replace(r, remainingProgress.toString());

			r = /\{3\}/g;
			format = format.replace(r, percentageRemaining.toString());

			r = /\{PercentageRemaining\}/g;
			format = format.replace(r, percentageRemaining.toString());

			r = /\{4\}/g;
			format = format.replace(r, self.min);

			r = /\{Min\}/g;
			format = format.replace(r, self.min);

			r = /\{5\}/g;
			format = format.replace(r, self.max);

			r = /\{Max\}/g;
			format = format.replace(r, self.max);

			return format;
		},

		destroy: function () {
			var self = this,
				element = self.element;

			element.empty().attr("aria-valuemax", "")
				.attr("aria-valuemin", "")
				.attr("aria-valuenow", "")
				.attr("title", "")
				.attr("role", "")
				.removeClass(wijpbCss + " " +
					wijpbCss + "-east " + wijpbCss + "-west " +
					wijpbCss + "-north " + wijpbCss + "-south");

			$.Widget.prototype.destroy.apply(this, arguments);
		}
	});

	$.extend($.wijmo.wijprogressbar, {
		animations: {
			progress: function (options, additions) {
				options = $.extend({
					easing: "swing",
					duration: 1000
				}, options, additions);
				options.content.stop(true, true).animate({
					//Because jquery's animation needs an
					//attribute or css style to do the animation,
					//here we use a temporary attribute to
					//do the animation on the value div element.
					pgvalue: options.progressValue
				}, options);
			}
		}
	});
}(jQuery));
