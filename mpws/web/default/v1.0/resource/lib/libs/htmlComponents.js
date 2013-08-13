/*
 * Library: Html Components Generator
*/

qB.Modules.register("lib/htmlComponents", [
    /* import globals */
    window
], [
    'lib/jquery',
    'lib/underscore',
    'lib/moment',
    'lib/jquery_ui',
    'lib/daterangepicker'
    /* component implementation */
], function(window, qB, Sandbox, $, _, moment) {

    function HtmlComponents () {}

    /* elements */
    HtmlComponents.prototype.generateTag = function (options) {
        var $tag = $('<' + options.tag + '>');
        if (options.attr)
            $tag.attr(options.attr);
        if (options.text)
            $tag.text(options.text);
        if (options.html)
            $tag.html(options.html);
        return $tag;
    }

    HtmlComponents.prototype.getTemplate = function (tpl) {
        return $(tpl).html();
    }

    HtmlComponents.prototype.getTemplateFn = function (tpl) {
        return _.template($(tpl).html());
    }

    HtmlComponents.prototype.compileTemplate = function (tpl, options) {
        if (tpl)
            return tpl(options);
    }

    HtmlComponents.prototype.generateTableCell = function (options) {

        // set default tag
        options.cell.tag = options.cell.tag || 'td';

        var $cell = this.generateTag(options.cell);
        if (Array.isArray(options.wrapper)) {
            var wrappers = [];

            var i = 0;

            for (i = 0, len = options.wrapper.length; i < len; i++)
                wrappers.push(this.generateTag(options.wrapper[i]));

            for (i = wrappers.length - 1; i >= 0; i--)
                $(wrappers[i - 1]).append($(wrappers[i]));

            $cell.append(wrappers[0]);
        }
        else
            if (typeof options.wrapper === "object") {
                options.wrapper.tag = options.wrapper.tag || 'div';
                $cell.append(this.generateTag(options.wrapper));
            }

        if (options.asString)
            return $cell.get(0).outerHTML;

        return $cell;
    }

    HtmlComponents.prototype.generateInputSelect = function (options) {

        var self = this;

        // make select tag
        var _select = this.generateTag({
            asString: true,
            tag : 'select',
            attr : {
                type: "text",
                class: "control control-select"
            }
        });

        // setup select options
        var _select_options = _(options.data).map(function(entry){
            var _attr = {
                value : entry[options.keyValue]
            };
            // set selected value accrding to user role id
            if (options.selectedValue === entry.id)
                _attr.selected = "selected";
            // return jquery option object
            return self.generateTag({
                tag: 'option',
                attr : _attr,
                text : entry[options.keyDisplay]
            });

        });

        // prepend default options
        _select_options.unshift(
            this.generateTag({
                tag: 'option',
                attr : {
                    value: ""
                },
                text : "None"
            })
        );

        _select.append(_select_options);

        return _select
    }

    HtmlComponents.prototype.getModalWindow = function (content, options) {

        var _css = this.getUniqueCssAttr('component-modal-window');

        var _dialog = this.generateTag({
            tag: 'div',
            attr: {
                id: _css.cssID,
                class:  _css.cssClassStr
            },
            html: content
        });

        // remove window from dom when closed
        var _userCloseFn = options.close;

        options.close = function () {
            if (typeof _userCloseFn === "function")
                _userCloseFn();

            $(this).remove();
        }

        var _dialogWnd = $(_dialog).dialog(options);

        // custom dialog methods
        _dialogWnd.open = function () {
            _dialogWnd.dialog("open");
        }

        return _dialogWnd;
    }

    HtmlComponents.prototype.getLabel = function (value, cssClass) {
        return this.generateTag({
            tag: 'span',
            attr: {
                class: "label" + (!!cssClass ? " label-" + cssClass : "")
            },
            text: value
        });
    }

    HtmlComponents.prototype.getLabelError = function (value) {
        var _lbl = this.getLabel(value, "error render-errors");

        _lbl.addClass('render-hidden');

        return _lbl;
    }

    HtmlComponents.prototype.getTextIcon = function (name) {
        return this.generateTag({
            tag: 'div',
            attr: {
                class: "icon icon-" + (!!name ? name : "default")
            }
        });
    }

    HtmlComponents.prototype.getSpacer = function () {
        return this.generateTag({
            tag: 'div',
            attr: {
                class: "spacer"
            }
        });
    }

    HtmlComponents.prototype.getWrapper = function (type) {
        return this.generateTag({
            tag: 'div',
            attr: {
                class: "wrapper-" + (type || "default")
            }
        });
    }

    HtmlComponents.prototype.getSimpleInput = function (options, events) {

        // control wrapper
        var _wrapper = this.getWrapper('control');

        // input element
        var _elem = this.generateTag({
            tag: 'input',
            attr: {
                class: "control-input" + (!!options.cssClass ? " " + options.cssClass : "")
            }
        });

        // set input value
        if (options.value)
            _elem.val(options.value);

        // add helper
        if (options.messages.helper) {
            _wrapper.append(this.getLabel(options.messages.helper));
            _wrapper.append(this.getSpacer());
        }

        // attach events
        if (events)
            _(events).each(function(eventFn, eventID){
                _elem.on(eventID, eventFn);
            });

        // add input into wrapper
        _wrapper.append(_elem);
        _wrapper.append(this.getSpacer());

        // add error message
        if (options.messages.error) {
            var _msgError = this.getLabelError(options.messages.error);
            _wrapper.append(_msgError);
            _wrapper.append(this.getSpacer());
        }

        // attach validators
        if (options.validators)
            _(options.validators).each(function(vobj){
                if (!vobj.fn || typeof vobj.fn !== "function")
                    return;

                var args = [_elem];

                if (vobj.options && $.isArray(vobj.options))
                    vobj.fn.apply(null, args.concat(vobj.options));
                else
                    vobj.fn.apply(null, args);
            });

        return {
            html: _wrapper,
            getInput: function () {
                return _elem;
            },
            showErrorMessage: function () {
                _msgError.removeClass('render-hidden');
            },
            setValue: function (value) {
                _elem.val(value);
            },
            getValue: function (filterFn) {
                if (filterFn)
                    return filterFn(_elem.val());
                return _elem.val();
            }
        };
    }

    HtmlComponents.prototype.getImage = function (options) {
        if (options)
            options.class = "image " + options.class;

        return this.generateTag({
            tag: 'img',
            attr: options
        });
    }

    HtmlComponents.prototype.getElement = function (name) {
        return this.generateTag({
            tag: 'div',
            attr: {
                class : "element element-" + name
            }
        });
    }

    HtmlComponents.prototype.widgetDateTimeRangeSelectBox = function (events) {

        var _wrapper = this.getWrapper('widget');

        var _dateReport = this.getLabel(moment());

        var _elem = this.generateTag({
            tag: 'div',
            attr: {
                class: 'control-widget control-widget-daterange'
            }
        });
        
        var _fnUpdateReportLabel = function(start, end) {

            $(_dateReport).html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));

            Sandbox.eventNotify('widget-datetime-range-changed', {
                start: start,
                end: end
            });
        }

        _elem.append(this.getTextIcon('calendar'));
        _elem.append(_dateReport);
        _elem.append(this.getElement('arrow-dn'));
        
        // add input into wrapper
        _wrapper.append(_elem);
        _wrapper.append(this.getSpacer());

        $(_elem).daterangepicker({
                ranges: {
                    // 'Today': [moment(), moment()],
                    // 'Today2': [moment().subtract('days', 5), moment()],
                    // 'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    // 'Last 7 Days': [moment().subtract('days', 6), moment()],
                    // 'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().utc().startOf('month'), moment().utc().endOf('month')],
                    'Last Month': [moment().utc().subtract('month', 1).startOf('month'), moment().utc().subtract('month', 1).endOf('month')]
                }
            },
            _fnUpdateReportLabel
        );

        _fnUpdateReportLabel(moment(), moment());

        // attach events
        if (events)
            _(events).each(function(eventFn, eventID){
                _wrapper.on(eventID, eventFn);
            });

        return {
            html: _wrapper,
            getInput: function () {
                return _elem;
            },
            setValue: function (value) {
                _elem.val(value);
            },
            getValue: function (filterFn) {
                if (filterFn)
                    return filterFn(_elem.val());
                return _elem.val();
            }
        };

    }


    HtmlComponents.prototype.widgetArrayLinks = function (config) {

        // control wrapper
        var _wrapper = this.getWrapper('widget');

        if (config.links) 
            _(config.links).each(function (wgtConfig, wgtKey) {
                
                /* put links here */

                // attach events
                if (wgtConfig.events)
                    _(wgtConfig.events).each(function(eventFn, eventID){
                        _wrapper.on(eventID, eventFn);
                    });

                _wrapper.append(this.getSpacer());
            });

        return {
            html: _wrapper
        }

    }

    HtmlComponents.prototype.widgetPanel = function (config) {

        // control wrapper
        var _wrapper = this.getWrapper('widget');

        var self = this;

        if (config.widgets)
            _(config.widgets).each(function (wgtConfig, wgtKey) {

                // skip unexisted widget fn
                if (typeof this['widget' + wgtKey] !== 'function')
                    return;

                var _wgt = this['widget' + wgtKey].apply(self, wgtConfig);

                _wrapper.append(_wgt.html);
                _wrapper.append(this.getSpacer());


            });

        return {
            html: _wrapper
        }
    }

    /* attributes */

    HtmlComponents.prototype.getUniqueCssAttr = function (baseClass) {
        var _uid = (new Date()).getTime();
        
        var _cssClassNames = [baseClass, baseClass + "_" + _uid];
        
        return {
            cssClassList: _cssClassNames,
            cssClassStr: _cssClassNames.join(' '),
            cssID: _cssClassNames.pop() + 'ID'
        }
    }

    HtmlComponents.prototype.getUniqueCssClass = function (baseClass) {
        return this.getUniqueCssAttr(baseClass).cssClassStr;
    }

    HtmlComponents.prototype.getUniqueCssID = function (baseClass) {
        return this.getUniqueCssAttr(baseClass).cssID;
    }


    /* components */


    HtmlComponents.prototype.messageBox = function (message, config) {
        // setup dialog options
        var _dialogOptions = _.extend({
            resizable: false,
            width: 500,
            modal: true,
            buttons: {
                OK: function () {
                    $(this).dialog("close");
                }
            }
        }, config);

        // just show dialog window
        this.getModalWindow(message, _dialogOptions);
    }


    return HtmlComponents;

});