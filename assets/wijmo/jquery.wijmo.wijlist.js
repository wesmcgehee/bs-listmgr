/*globals jQuery*/
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
 * * Wijmo List widget.
 *
 * Depends:
 *  jquery.ui.core.js
 *  jquery.ui.widget.js
 *  jquery.ui.wijsuperpanel.js
 *  
 */
(function ($) {
	"use strict";
	var listCSS = "ui-widget ui-widget-content ui-corner-all wijmo-wijlist",
		listItemCSS = "wijmo-wijlist-item",
		listItemCSSAlternate = listItemCSS + "-alternate",
		listItemCSSSelected = listItemCSS + "-selected",
		listItemCSSFirst = listItemCSS + "-first",
		listItemCSSLast = listItemCSS + "-last",
		stateHover = "ui-state-hover",
		uiStateActive = "ui-state-active",
		activeItem = "wijmo-wijlistitem-active",
		selectedActive = listItemCSSSelected + " " + uiStateActive,
		itemKey = "item.wijlist";
	$.widget("wijmo.wijlist", {
		options: {
			/// <summary>
			/// An array that specifies the listItem collections of wijlist.
			/// Example: listItems: [{label: "label1", value: "value1"},
			///                  {label: "label2", value: "value2"},
			///                  {label: "label3", value: "value3"}]
			/// Default: [].
			/// Type: Array.
			/// </summary>
			listItems: [],
			/// <summary>
			/// Select event handler of wijlist. A function will be called 
			/// when any item in the list is selected.
			/// Default: null.
			/// Type: Function.
			/// Code example: 
			/// Supply a callback function to handle the selected event:
			/// $("#element").wijlist({ selected: function (e, data) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistselected", function(e, data) { });
			/// </summary>
			/// <param name="e" type="eventObj">
			/// The jquery event object.
			///	</param>
			/// <param name="data" type="Object">
			/// By data.item to obtain the item selected. 
			/// By data.item.element to obtain the li DOM element selected.
			///	</param>
			selected: null,
			/// <summary>
			/// A value indicates the selection mode of wijlist.
			/// Default: "single".
			/// Type: String.
			/// </summary>
			/// <remarks>
			/// Options are "single" and "multiple". This option should not be set 
			/// again after initialization.
			/// </remarks>
			selectionMode: "single",
			/// <summary>
			/// A value determines whether to auto-size wijlist.
			/// Default: false.
			/// Type: String.
			/// </summary>
			autoSize: false,
			/// <summary>
			/// A value specifies the max items count to display if 
			///autoSize is set to true.
			/// Default: 5.
			/// Type: Number.
			/// </summary>
			maxItemsCount: 5,
			/// <summary>
			/// A value determines whether to add ui-state-hover class to list
			/// item when mouse enters.
			/// Default: true.
			/// Type: Boolean.
			/// </summary>
			addHoverItemClass: true,
			/// <summary>
			/// A hash value sets to supepanel options when superpanel is created.
			/// Default: null.
			/// Type: Object.
			/// </summary>
			superPanelOptions: null,
			/// <summary>
			/// A value indicates whether wijlist is disabled.
			/// Default: false.
			/// Type: Boolean.
			/// </summary>
			disabled: false,
			/// <summary>
			/// A function called before an item is focused.
			/// Default: null.
			/// Type: Function.
			/// Supply a callback function to handle the focusing event:
			/// $("#element").wijlist({ focusing: function (e, item) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistfocusing", function(e, item) { });
			/// </summary>
			/// <param name="event" type="EventObject">
			/// The jquery event object, event object passed in to activate method.
			/// </param>
			/// <param name="item" type="Object">
			/// item to be rendered.
			/// item.element: LI element with this item.
			/// item.list: wijlist instance.
			/// item.label: label of item.
			/// item.value: value of item.
			/// item.text: could be set in handler to override rendered label of item.
			/// </param>
			/// <returns>
			/// returns false to cancel item focusing.
			/// </returns>
			focusing: null,
			/// <summary>
			/// A function called after an item is focused.
			/// Default: null.
			/// Type: Function.
			/// Supply a callback function to handle the focus event:
			/// $("#element").wijlist({ focus: function (e, item) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistfocus", function(e, item) { });
			/// </summary>
			/// <param name="event" type="EventObject">
			/// The jquery event object, event object passed in to activate method.
			/// </param>
			/// <param name="item" type="Object">
			/// item to be rendered.
			/// item.element: LI element with this item.
			/// item.list: wijlist instance.
			/// item.label: label of item.
			/// item.value: value of item.
			/// item.text: could be set in handler to override rendered label of item.
			/// </param>
			focus: null,
			/// <summary>
			/// A function called when an item loses focus.
			/// Type: Function.
			/// Default: null.
			/// Supply a callback function to handle the blur event:
			/// $("#element").wijlist({ blur: function (e, item) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistblur", function(e, item) { });
			/// </summary>
			/// <param name="event" type="EventObject">
			/// The jquery event object, event object passed in to activate method.
			/// </param>
			/// <param name="item" type="Object">
			/// item to be rendered.
			/// item.element: LI element with this item.
			/// item.list: wijlist instance.
			/// item.label: label of item.
			/// item.value: value of item.
			/// item.text: could be set in handler to override rendered label of item.
			/// </param>
			blur: null,
			/// <summary>
			/// A function called before an item is rendered.
			/// Default: null.
			/// Type: Function.
			/// Supply a callback function to handle the itemRendering event:
			/// $("#element").wijlist({ itemRendering: function (e, item) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistitemRendering", function(e, item) { });
			/// </summary>
			/// <param name="event" type="EventObject">
			/// The jquery event object.
			/// </param>
			/// <param name="item" type="Object">
			/// item to be rendered.
			/// item.element: LI element with this item.
			/// item.list: wijlist instance.
			/// item.label: label of item.
			/// item.value: value of item.
			/// item.text: could be set in handler to override rendered label of item.
			/// </param>
			itemrendering: null,
			/// <summary>
			/// A function called after a item is rendered.
			/// Default: null.
			/// Type: Function.
			/// Supply a callback function to handle the itemRendered event:
			/// $("#element").wijlist({ itemRendered: function (e, item) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistitemRendered", function(e, item) { });
			/// </summary>
			/// <param name="event" type="EventObject">
			/// The jquery event object.
			/// </param>
			/// <param name="item" type="Object">
			/// item to be rendered.
			/// item.element: LI element with this item.
			/// item.list: wijlist instance.
			/// item.label: label of item.
			/// item.value: value of item.
			/// item.text: could be set in handler to override rendered label of item.
			/// </param>
			itemrendered: null,
			/// <summary>
			/// A function called after list is rendered.
			/// Default: null.
			/// Type: Function.
			/// Supply a callback function to handle the listRendered event:
			/// $("#element").wijlist({ listRendered: function (e, list) { } });
			/// Bind to the event by type:
			/// $("#element").bind("wijlistlistRendered", function(e, list) { });
			/// </summary>
			/// <param name="event" type="EventObject">
			/// The jquery event object.
			/// </param>
			/// <param name="list" type="Object">
			/// list rendered.
			/// </param>
			listrendered: null,
			/// <summary>
			/// A value determines whether to keep item highlight when mouse 
			/// is leaving list. 
			/// Default: Boolean.
			/// Type: false.
			/// </summary>
			keepHightlightOnMouseLeave: false
		},

		removeAll: function () {
			var self = this;
			self.items = [];
			self._refresh();
		},

		addItem: function (item, index) {
			var self = this;
			self._checkData();
			if (index == null) {
				self.items.push(item);
			}
			else {
				self.items.splice(index, 0, item);
			}
			self._refresh();
		},

		removeItem: function (item) {
			var self = this;
			self._checkData();
			var index = self.indexOf(item);
			if (index >= 0) {
				self.removeItemAt(index);
			}
		},

		indexOf: function (item) {
			var self = this, index = -1, i = 0, oItem;
			self._checkData();
			for (i = 0; i < self.items.length; i++) {
				oItem = self.items[i];
				if (oItem.label == item.label && oItem.value == item.value) {
					index = i;
					break;
				}
			}
			return index;
		},

		removeItemAt: function (index) {
			var self = this;
			self._checkData();
			self.items.splice(index, 1);
			self._refresh();
		},

		_checkData: function () {
			var self = this;
			if (self.items == null) {
				self.items = [];
			}
		},

		_refresh: function () {
			var self = this;
			self.renderList();
			self.refreshSuperPanel();
		},

		_create: function () {
			var self = this, ele = this.element, o = this.options;
			ele.addClass(listCSS).attr({
				role: "listbox",
				"aria-activedescendant": activeItem,
				"aria-multiselectable": o.selectionMode === "multiple"
			}).bind("click." + self.widgetName, self, self._onListClick);
			self.ul = $("<ul class='wijmo-wijlist-ul'></ul>").appendTo(ele);
			if (o.disabled) {
				self.disable();
			}
			if (o.listItems != null) {
				if (o.listItems.length > 0) {
					self.setItems(o.listItems);
					self.renderList();
					self.refreshSuperPanel();
				}
			}
		},

		setItems: function (items) {
			/// <summary>
			/// Sets Items to be rendered by the wijlist.
			/// </summary>
			/// <param name="items" type="Array">
			/// Items array to be rendered.  The array contains object like 
			///{label: "label", value: "value"}.
			/// </param>
			var self = this, selectedItems;

			self.items = items;
			selectedItems = $.grep(items, function (a) {
				return a.selected;
			});
			if (self.options.selectionMode === "single") {
				self.selectedItems = [];
				self.selectedItem = selectedItems.length > 0 ?
									selectedItems[0] : undefined;
			}
			else {
				self.selectedItems = selectedItems;
			}
		},

		popItem: function () {
			var self = this;
			self._checkData();
			self.items.pop();
			self._refresh();
		},

		getList: function () {
			/// <summary>
			/// Gets the JQuery object reference of the ul element of wijlist.
			/// </summary>
			/// <returns type="JQueryObj">
			/// ul JQuery reference.
			/// </returns>

			return this.ul;
		},

		_onListClick: function (e) {
			if (!$(e.target).closest(".wijmo-wijlist-item").length) {
				return;
			}
			var self = e.data;
			self.select(e);
		},

		destroy: function () {
			/// <summary>
			/// Destroys wijlist.
			/// </summary>

			var self = this, ele = this.element;
			if (self.superPanel !== undefined) {
				self.superPanel.destroy();
			}

			ele.removeClass(listCSS).removeAttr("role")
			.removeAttr("aria-activedescendant").unbind("." + self.widgetName);
			self.ul.remove();
			$.Widget.prototype.destroy.apply(self, arguments);
		},

		activate: function (event, item, scrollTo) {
			///	<summary>
			///		Activates a wijlist item.
			///	</summary>
			/// <param name="event" type="EventObject">
			/// Event will raise activation.
			/// </param>
			/// <param name="item" type="wijlistItem">
			/// wijlistItem to activate.
			///	</param>
			/// <param name="scrollTo" type="Boolean">
			/// Whether to scroll activated item to view.
			///	</param>

			var self = this, active, activeElement;
			self.deactivate();
			if (item === null || item === undefined) {
				return;
			}
			if (self._trigger("focusing", event, item) === false) {
				return;
			}
			active = self.active = item;
			activeElement = item.element;
			if (self.options.addHoverItemClass) {
				activeElement.addClass(stateHover);
			}
			activeElement.attr("id", activeItem);
			if (scrollTo && self.superPanel !== undefined) {
				self.superPanel.scrollChildIntoView(activeElement);
			}
			self._trigger("focus", event, item);
		},

		deactivate: function () {
			/// <summary>
			/// Deactivates activated items.
			/// </summary>

			var self = this,
				a = self.active, ele;
			if (!a) {
				return;
			}
			ele = a.element;
			self._trigger("blur", null, a);
			ele.removeClass(stateHover).removeAttr("id");
			self.active = undefined;
		},

		next: function (event) {
			/// <summary>
			/// Moves focus to the next item. 
			/// </summary>

			this.move("next", "." + listItemCSS + ":first", event);
		},

		nextPage: function () {
			/// <summary>
			/// Turns to the next page of the list.
			/// </summary>

			this.superPanel.doScrolling("bottom", true);
		},

		previous: function (event) {
			/// <summary>
			/// Moves focus to the previous item. 
			/// </summary>

			this.move("prev", "." + listItemCSS + ":last", event);
		},

		previousPage: function () {
			/// <summary>
			/// Turns to the previous page of the wijlist.
			/// </summary>

			this.superPanel.doScrolling("top", true);
		},

		first: function () {
			/// <summary>
			/// Tests that the focus is at the first item.
			/// </summary>

			return this.active && !this.active.element.prev().length;
		},

		last: function () {
			/// <summary>
			/// Tests that the focus is at the last item.
			/// </summary>

			return this.active && !this.active.element.next().length;
		},

		move: function (direction, edge, event) {
			/// <summary>
			/// Move focus between items.
			/// </summary>

			var self = this, item, next;
			if (!self.active) {
				item = self.ul.children(edge).data(itemKey);
				self.activate(event, item, true);
				return;
			}
			next = self.active.element[direction + "All"]("." + listItemCSS).eq(0);
			if (next.length) {
				self.activate(event, next.data(itemKey), true);
			}
			else {
				self.activate(event, self.element.children(edge).data(itemKey), true);
			}
		},

		select: function (event, data) {
			/// <summary>
			/// Selects active list item.
			/// </summary>
			///

			var self = this,
				ele = self.active.element,
				item, singleMode, previous;
			if (ele === undefined) {
				return;
			}
			item = ele.data(itemKey);
			singleMode = self.options.selectionMode === "single";
			if (singleMode) {
				previous = self.selectedItem;
				ele.addClass(selectedActive).attr("aria-selected", "true");
				item.selected = true;
				if (previous !== undefined && item !== previous) {
					previous.selected = false;
					previous.element.removeClass(selectedActive)
					.removeAttr("aria-selected");
				}
				self.selectedItem = item;
				self._trigger("selected", event, {
					item: item,
					previousItem: previous,
					data: data
				});
			}
			else {
				item.selected = !item.selected;
				if (item.selected) {
					ele.addClass(selectedActive).attr("aria-selected", "true");
				}
				else {
					ele.removeClass(selectedActive).removeAttr("aria-selected", "true");
				}
				self.selectedItems = $.grep(self.items, function (a) {
					return a.selected;
				});
				self._trigger("selected", event, {
					item: item,
					selectedItems: self.selectedItems
				});
			}
		},

		_findItemsByValues: function (values) {
			var itemFound, found = [];

			found = $.grep(this.items, function (itm, i) {
				itemFound = false;
				for (var j = 0; j < values.length; j++) {
					if (itm.value === values[j]) {
						itemFound = true;
					}
				}
				return itemFound;
			});

			return found;
		},

		_findItemsByIndices: function (indices) {
			var self = this, len = this.items.length, found = [];

			$.each(indices, function (index, value) {
				if (value >= 0 && value < len) {
					found.push(self.items[value]);
				}
			});

			return found;
		},

		getItems: function (indices) {
			/// <summary>
			/// Find list items by indices or values.
			/// </summary>
			/// <param name="indices" type="Array/Number">
			/// This parameter could be a string, number, array of string,
			/// array of number.
			/// If parameter is a number or an array of number,
			/// it's used as the index/indices of the item(s) to get.
			/// If parameter is a string or an array of string,
			/// it's used as the value/values of the item(s) to get.
			/// </param>

			var self = this, isNumber, byArray, searchTerms, foundItems;

			byArray = $.isArray(indices);
			isNumber = (!byArray) && !isNaN(indices) || (byArray && !isNaN(indices[0]));
			searchTerms = byArray ? indices : [indices];
			foundItems = isNumber ?
			self._findItemsByIndices(searchTerms) : self._findItemsByValues(searchTerms);
			return foundItems;
		},

		selectItems: function (indices, triggerSelected) {
			/// <summary>
			/// Selects item(s) in the list by item index/indices or value(s).
			/// </summary>
			/// <param name="indices" type="Array/Number">
			/// This parameter could be a string, number, array of string,
			/// array of number.
			/// If parameter is a number or an array of number,
			/// it's used as the index/indices of the item(s) to get.
			/// If parameter is a string or an array of string,
			/// it's used as the value/values of the item(s) to get.
			/// </param>
			/// <param name="triggerSelected" type="Boolean">
			/// Whether to trigger selected event of list.
			/// </param>

			var self = this, singleMode = this.options.selectionMode === "single",
			item, previous, foundItems;

			foundItems = self.getItems(indices);
			if (singleMode) {
				if (foundItems.length > 0) {
					item = foundItems[0];
					item.selected = true;
					item.element.addClass(selectedActive);
				}
				previous = self.selectedItem;
				if (previous) {
					previous.selected = false;
					previous.element.removeClass(selectedActive);
				}
				self.selectedItem = item;
				if (triggerSelected) {
					self._trigger("selected", null, {
						item: item,
						previousItem: previous
					});
				}
			}
			else {
				$.each(foundItems, function (index, itm) {
					itm.selected = true;
					itm.element.addClass(selectedActive);
				});
				self.selectedItems = $.grep(self.items, function (a) {
					return a.selected;
				});
				if (triggerSelected) {
					self._trigger("selected", null, {
						selectedItems: self.selectedItems
					});
				}
			}
		},

		unselectItems: function (indices) {
			/// <summary>
			/// Unselects items by itemsÃ¢â‚¬â„¢indices.
			/// </summary>
			/// <param name="indices" type="Array">
			/// Indices of items to unselect.
			/// </param>

			var self = this, mode = this.options.selectionMode, selectedItem, foundItems;

			if (mode === "single") {
				selectedItem = self.selectedItem;
				if (selectedItem) {
					selectedItem.selected = false;
					selectedItem.element.removeClass(selectedActive);
					self.selectedItem = undefined;
				}
			}
			else {
				foundItems = self.getItems(indices);
				$.each(foundItems, function (index, i) {
					i.selected = false;
					i.element.removeClass(selectedActive);
				});
				self.selectedItems = $.grep(self.items, function (a) {
					return a.selected;
				});
			}
		},

		renderList: function () {
			/// <summary>
			/// Render items of wijlist.
			/// </summary>
			var self = this, ul = this.ul, o = this.options, items,
			count, singleMode, i, item;
			ul.empty();
			// returns if no items to render.
			items = self.items;
			if (items === undefined) {
				return;
			}
			count = items.length;
			if (items === undefined || items === null && count === 0) {
				return;
			}
			singleMode = o.selectionMode === "single";
			for (i = 0; i < count; i++) {
				item = items[i];
				self._renderItem(ul, item, i, singleMode);
			}
			if (count > 0) {
				items[0].element.addClass(listItemCSSFirst);
				items[count - 1].element.addClass(listItemCSSLast);
			}
			self._trigger("listrendered", null, self);
		},


		_renderItem: function (ul, item, index, singleMode) {
			var self = this,
			li = $("<li role='option' class='wijmo-wijlist-item " +
			"ui-corner-all'></li>"), label, url;
			item.element = li;
			item.list = self;
			if (self._trigger("itemrendering", null, item) === false) {
				return;
			}
			label = item.label;
			// if text is set, text will override label value.
			if (item.text !== undefined) {
				label = item.text;
			}
			// binds list item event
			li.bind("mouseover", function (event) {
				self.activate(event, item, false);
			}).bind("mouseout", function () {
				if (!self.options.keepHightlightOnMouseLeave) {
					self.deactivate();
				}
			}).data(itemKey, item).append(label).appendTo(ul);
			// render image
			url = item.imageUrl;
			if (url !== undefined && url.length > 0) {
				li.prepend("<img src='" + item.imageUrl + "'>");
			}
			// add selected items
			if (item.selected) {
				self.activate(null, item, false);
				li.addClass(selectedActive);
			}
			if (index % 2 === 1) {
				li.addClass(listItemCSSAlternate);
			}
			self._trigger("itemrendered", null, item);
		},

		refreshSuperPanel: function () {
			/// <summary>
			/// Reset the layout of superpanel to reflect the change in content.
			/// </summary>

			var self = this, ele = this.element, o = this.options, ul = this.ul,
			singleItem = ul.children(".wijmo-wijlist-item:first"),
			adjustHeight = null, h, percent, small, vScroller, large, spOptions, pt;
			if (!ele.is(":visible")) {
				return false;
			}
			if (o.autoSize) {
				adjustHeight = singleItem.outerHeight(true) * o.maxItemsCount;
			}

			if (adjustHeight !== null) {
				ele.height(Math.min(adjustHeight, ul.outerHeight()));
			}
			h = ele.innerHeight();
			percent = h / (ul.outerHeight() - h);
			large = (101 * percent) / (1 + percent);
			small = (singleItem.outerHeight() / (ul.outerHeight() - h)) * (101 - large);
			if (self.superPanel === undefined) {
				spOptions = {
					allowResize: false,
					keyboardSupport: false,
					bubbleScrollingEvent: true,
					hScroller: {
						scrollBarVisibility: "hidden"
					},
					vScroller: {
						scrollSmallChange: small,
						scrollLargeChange: large
					}
				};

				$.extend(spOptions, o.superPanelOptions);
				self.superPanel = ele.wijsuperpanel(spOptions).data("wijsuperpanel");
			}
			else {
				vScroller = self.superPanel.options.vScroller;
				vScroller.scrollLargeChange = large;
				vScroller.scrollSmallChange = small;
				self.superPanel.paintPanel();
			}
			pt = ul.css("padding-top");
			if (pt.length > 0) {
				vScroller = self.superPanel.options.vScroller;
				vScroller.firstStepChangeFix = self.superPanel
				.scrollPxToValue(parseFloat(pt), "v");
			}
			else {
				vScroller.firstStepChangeFix = 0;
			}
			ul.setOutWidth(ul.parent().parent().innerWidth());
		}
	});
} (jQuery));