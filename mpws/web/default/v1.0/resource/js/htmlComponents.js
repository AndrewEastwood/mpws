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
    'lib/daterangepicker',
    'lib/datetimepicker',
    'lib/bootstrap',
    'lib/jquery.maskedinput'
    /* component implementation */
], function(window, qB, Sandbox, $, _, moment) {

    function HtmlComponents () {}

    /* consts */
    HtmlComponents.CSS_RENDER_READONLY = 'render-readonly';
    HtmlComponents.CSS_RENDER_HIDDEN = 'render-hidden';
    HtmlComponents.CSS_RENDER_GOST = 'render-gost';
    HtmlComponents.CSS_RENDER_DISABLED = 'render-disabled';
    HtmlComponents.CSS_RENDER_LOADING = 'render-loading';
    HtmlComponents.CSS_RENDER_LABEL = 'render-label';
    HtmlComponents.CSS_RENDER_VALUE = 'render-value';
    HtmlComponents.CSS_RENDER_CONTROL = 'render-control';
    HtmlComponents.CSS_RENDER_ERRORS = 'render-errors';
    HtmlComponents.CSS_RENDER_EDIT = 'render-edit';
    HtmlComponents.CSS_RENDER_ACTIVE = 'render-active';
    HtmlComponents.CSS_ELEM_ICON = 'icon';

    /* elements */
    HtmlComponents.prototype.generateTag = function (options) {

        if (typeof options === "string") {
            return this.generateTag({tag: options});
        }

        var $tag = $('<' + options.tag + '>');
        if (options.attr)
            $tag.attr(options.attr);
        if (options.text)
            $tag.text(options.text);
        if (options.html)
            $tag.html(options.html);
        return $tag;
    }

    HtmlComponents.prototype.generateInputSelect = function (options) {

        var self = this;

        // make select tag
        var _select = this.generateTag({
            asString: true,
            tag : 'select',
            attr : {
                type: null,
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
            }
        });

        // adjust args
        if (content && !(content instanceof $) && !options) {
            options = content;
            content = false;
        }

        // remove window from dom when closed
        var _userCloseFn = options.close;

        options.close = function () {
            if (typeof _userCloseFn === "function")
                _userCloseFn();

            $(this).remove();
        }

        options.dialogClass = this.setCssScope("qb-simple-popup", options.dialogClass);

        // _dialog.append(content);
        var _autoOpen = options && (options.autoOpen || typeof options.autoOpen === "undefined");
        options.autoOpen = false;

        var _dialogWnd = $(_dialog).dialog(options);

        _dialogWnd.data( "uiDialog" )._title = function(title) {
            title.html( this.options.title );
        };

        _dialogWnd.data( "qbInitial", _dialog );

        // custom dialog methods
        _dialogWnd.open = function (content) {
            if (content)
                this.setContent(content);
            _dialogWnd.dialog("open");
            if (options.events && _.isFunction(options.events.onOpen))
                options.events.onOpen.call(_dialog);
            // check wheter we render it as tooltip
            // if (options.qbShowAsTooltipFor) {
                // qB.log(true, 'render near ', options.qbShowAsTooltipFor);
            // }
        }

        _dialogWnd.setContent = function (content) {
            _dialog.append(content);
        }

        if (content)
            _dialogWnd.setContent(content);
        // window.dlg = _dialogWnd;

        // attach events
        this.attachEvents(options.events, _dialogWnd.parent());

        if (_autoOpen)
            _dialogWnd.open();

        return _dialogWnd;
    }

    HtmlComponents.prototype.getLink = function (options) {
        
        if (typeof options === "string")
            options = {
                href: options
            };

        var _options = $.extend(true, {}, {
            attr: {
                href: 'javascript://'
            }
        }, options, {tag : 'a'});

        _options.tag = 'a';

        // if (_options.attr.class)
            _options.attr.class = this.setCssScope("link", _options.attr.class);
        // else
            // _options.attr.class = "link";

        var _link = this.generateTag(_options);

        if (options.icon) {
            // var wrp = $('<wrp>')
            //     .append(_link)
            //     .append(this.getTextIcon(options.icon));
            // _link = wrp.contents();
            _link.wrapInner('<span>');
            _link.append(this.getTextIcon(options.icon));
        }

        // attach events
        this.attachEvents(options.events, _link);

        return _link;
    }

    HtmlComponents.prototype.getHeader = function (value, type) {
        return this.generateTag({
            tag: 'h' + (type || 3),
            text: value
        });
    }

    HtmlComponents.prototype.getLabel = function (value, cssClass) {
        return this.generateTag({
            tag: 'span',
            attr: {
                class: this.setCssScope("label", cssClass)
            },
            text: value
        });
    }

    HtmlComponents.prototype.getLabelWithIcon = function (value, icon, putIconBeforeLabel) {
        if (putIconBeforeLabel)
            return this.getWrapper("label-icon", this.getTextIcon(icon), this.getLabel(value));
        return this.getWrapper("label-icon", this.getLabel(value), this.getTextIcon(icon));
    }

    HtmlComponents.prototype.getLabelWarn = function (value) {
        var _lbl = this.getLabel(value, 'warning');
        _lbl.prepend(this.getTextIcon('notification'));
        return _lbl;
    }

    HtmlComponents.prototype.getLabelError = function (value) {
        var _lbl = this.getLabel(value, "error");

        _lbl.addClass('render-hidden');

        return _lbl;
    }

    HtmlComponents.prototype.getLabelHelp = function (value, iconPrepend, cssClass) {
        var _lbl = this.getLabel(value, "help");
        var cssClasses = ['help'];
        if (cssClass)
            cssClasses.push('custom-' + cssClass);
        if (iconPrepend)
            _lbl.prepend(this.getTextIcon(cssClasses));
        else
            _lbl.append(this.getTextIcon(cssClasses));
        return _lbl;
    }

    HtmlComponents.prototype.getLabelValue = function (label, value, cssClass) {
        var _cnt = this.getContainer(false, this.setCssScope("label-value", cssClass));

        var _lbl = this.getContainerBlock('label');
        var _val = this.getContainerBlock('value');

        _lbl.append(label);
        _val.append(value);

        _cnt.group.append(_lbl, _val);

        return {
            el: _cnt.el,
            container: _cnt
        }
    }

    HtmlComponents.prototype.getLabelCurrency = function (valueOnly) {
        if (valueOnly)
            return '$';
        return this.getLabel('$', 'currency');
    }

    HtmlComponents.prototype.getTextIcon = function (cssClass, options) {
        // var cssClass = 
        return this.generateTag({
            tag: 'div',
            attr: $.extend({}, options, {
                class: this.setCssScope("icon", cssClass),
                // "data-icon": "icon-" + cssClass
            })
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

    HtmlComponents.prototype.getControl = function (cssClass) {
        return this.generateTag({
            tag: 'div',
            attr: {
                class: this.setCssScope("control", cssClass)
            }
        });
    }

    HtmlComponents.prototype.getSpinner = function (spinnerName) {
        var _config = qB.Page.getConfiguration();
        return this.getImage({src:_config.URL.staticUrlImage + spinnerName});
    }

    // it is added just for convenience
    HtmlComponents.prototype.getStaticImage = function (imageName) {
        return this.getSpinner(imageName);
    }

    HtmlComponents.prototype.getWrapper = function (cssClass) {
        var _elements = [].slice.call(arguments, 1);

        var _wrapper = this.generateTag({
            tag: 'div',
            attr: {
                class: this.setCssScope("wrapper", cssClass)
            }
        });

        if (_elements)
            _wrapper.append(_elements);

        return _wrapper;
    }

    HtmlComponents.prototype.getContainer = function (skipGroup, name) {
        var _container = this.generateTag({
            tag: 'div',
            attr: {
                class: this.setCssScope("container", name)
            }
        });

        var _group = null;

        if (!skipGroup) {
            _group = this.generateTag({
                tag: 'div',
                attr: {
                    class: 'container-block-group'
                }
            });

            _container.append(_group);
        }

        return {
            html: _container,
            el: _container, // need to optimize
            group: _group,
            appendToContainer: function (element) {
                _container.append(element)
            },
            appendToGroup: function (element) {
                _group.append(element);
            }
        }
    }

    HtmlComponents.prototype.getContainerBlock = function (renderMode, content, name) {
        return this.generateTag({
            tag: 'div',
            attr: {
                class: this.setCssScope("container-block", name) + ' render-' + (renderMode || 'normal')
            },
            html: content || false
        });
    }

    // TODO: move events arg into options.events
    HtmlComponents.prototype.getSimpleInput = function (options, events) {

        // control wrapper
        options = $.extend({}, {
            cssClass: 'default',
            value: null,
            attr: {},
            messages: {}
        }, options);

        var cssClasses = ['control'];
        if (options.cssClass)
            cssClasses.push('control-' + options.cssClass);
        var _wrapper = this.getWrapper(cssClasses);

        // input element
        var _elem = this.generateTag({
            tag: 'input',
            attr: $.extend(
            // default options that we can overwrite
            {
                type: "text"
            },
            // our options
            options.attr,
            // this options we do not allow to redefine
            {
                class: this.setCssScope("control-input", options.cssClass),
                id: options.cssID ? "control-input-" + options.cssID + '-ID' : null
            })
        });

        // set input value
        if (options.value)
            _elem.val(options.value);

        // setup masked input
        if (options.maskedInput && options.maskedInput.format)
            _elem.mask(options.maskedInput.format, {} || options.maskedInput.options);

        // add helper
        if (options.messages.helper) {
            _wrapper.append(this.getLabel(options.messages.helper));
            _wrapper.append(this.getSpacer());
        }

        // attach events
        this.attachEvents(options.events || events, _elem);
        // if (events)
        //     _(events).each(function(eventFn, eventID){
        //         _elem.on(eventID, eventFn);
        //     });

        if (options.messages.beforeControlBlock) {
            _wrapper.append(options.messages.beforeControlBlock);
            _wrapper.append(this.getSpacer());
        }

        // add input into wrapper
        _wrapper.append(_elem);
        _wrapper.append(this.getSpacer());

        // add bottom helper
        if (options.messages.afterControlBlock) {
            _wrapper.append(options.messages.afterControlBlock);
            _wrapper.append(this.getSpacer());
        }

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

        var _attr = {};

        if (_.isString(options)) {
            _attr.src = options;
            _attr.class = "image";
        }
        else if (_.isObject(options)) {
            _attr = _.extend({}, _attr, options);
            _attr.class = this.setCssScope("image", options.class);
        }

        return this.generateTag({
            tag: 'img',
            attr: _attr
        });
    }

    HtmlComponents.prototype.getEmptySpace = function () {
        return "&nbsp;";
    }

    HtmlComponents.prototype.getElement = function (name) {
        return this.generateTag({
            tag: 'div',
            attr: {
                class : this.setCssScope("element", name)
            }
        });
    }

    HtmlComponents.prototype.getWidget = function (type, options, container) {
        var cssClasses = [];

        if (type)
            cssClasses.push(type);

        if (!container && options instanceof $) {
            container = options;
        } else {
            if (options.name)
                cssClasses.push(type + "-" + options.name);
            if (options.attr && options.attr.class)
                cssClasses.push(type + "-" + options.attr.class);
        }

        var attr = _.extend({}, options.attr || {}, {
            class: this.setCssScope("widget", cssClasses)//; cssClasses.join(" ").toLowerCase()
        })

        var _widget = this.generateTag({
            tag: 'div',
            attr: attr
        });

        // qB.log('getWidget >> adding container ', container)

        _widget.append(container.el);

        return {
            el: _widget,
            container: container
        }
    }

    /* widgets */
    HtmlComponents.prototype.widgetDateTimeRangeSelectBox = function (options) {

        var _widget = this.getWidget('DateTimeRangeSelectBox', options || {}, this.getContainer());
        // var _containerGroup = this.getContainerGroup();

        var _options = {
            label: "Period"
        };

        _options = _.extend({}, _options, options || {});

        // add control label
        if (_options.label) {
            var _containerBlockLabel = this.getContainerBlock('label');
            _containerBlockLabel.html(_options.label);
            _widget.container.group.append(_containerBlockLabel);
        }

        var _dateReport = this.getLabel(moment());
        var _rangesPresets = {
            'DEFAULT': {
                'Current Month': [moment().utc().startOf('month'), moment().utc().endOf('day')]
            },
            'WEEKBASED_1': {
                'Today': [moment().utc().startOf('day'), moment().utc().endOf('day')],
                'Since yesterday': [moment().utc().subtract('day', 1).startOf('day'), moment().utc().endOf('day')],
                'Last week': [moment().utc().subtract('week', 1).startOf('week'), moment().utc().subtract('week', 1).endOf('week')],
                'Last two weeks': [moment().utc().subtract('week', 2).startOf('week'), moment().utc().subtract('week', 1).endOf('week')],
                'Last 30 days': [moment().utc().subtract('month', 1).startOf('month'), moment().utc().subtract('month', 1).endOf('month')]
            },
            'WEEKBASED_FROMTODAY': {
                'Today': [moment().utc().startOf('day'), moment().utc().endOf('day')],
                'Since yesterday': [moment().utc().subtract('day', 1).startOf('day'), moment().utc().endOf('day')],
                'Last week': [moment().utc().subtract('day', 7).startOf('day'), moment().utc().endOf('day')],
                'Last two weeks': [moment().utc().subtract('day', 14).startOf('day'), moment().utc().endOf('day')],
                'Last 30 days': [moment().utc().subtract('day', 30).startOf('day'), moment().utc().endOf('day')]
            },
            'LONG': {}
        };
        var _ranges = _rangesPresets["DEFAULT"];

        if (typeof _options.ranges === "string" && _rangesPresets[_options.ranges])
            _ranges = _rangesPresets[_options.ranges];
        else if (typeof _options.ranges === "object")
            _ranges = _.extend(_ranges, _options.ranges);

        // cleanup empty ranges
        _(_(_ranges).clone()).each(function(v, k){
            if (_.isArray(v) && v.length === 2 && !!v[0] && !!v[1] && moment.isMoment(v[0]) && moment.isMoment(v[1])) {
                // qB.log('filtering', _ranges[k]);
                return;
            }
            delete _ranges[k];
        });

        var _fnUpdateReportLabel = function(start, end) {

            // qB.log(true, "htmlComponents >> _fnUpdateReportLabel", arguments);
            // var _label = start.format('MMM D, YYYY HH:mm:ss') + ' - ' + end.format('MMM D, YYYY HH:mm:ss');
            var _label = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
            var _labelAlias = false;
            var strFnStart = start.format('MMM D, YYYY HH:mm:ss');
            var strFnEnd = end.format('MMM D, YYYY HH:mm:ss');
            // qB.log('strFnStart  = ', strFnStart,  'strFnEnd  = ', strFnEnd);
            _(_ranges).each(function (dateRangeEntry, valueKey) {
                var strValStart = dateRangeEntry[0].format('MMM D, YYYY HH:mm:ss');
                var strValEnd = dateRangeEntry[1].format('MMM D, YYYY HH:mm:ss');
                // qB.log('-=-=-=-=-=');
                // qB.log('strValStart = ', strValStart, 'strValEnd = ', strValEnd);
                // qB.log('dateRangeEntry');
                // qB.log(dateRangeEntry[0].format('MMM D, YYYY HH:mm:ss'));
                // qB.log(dateRangeEntry[1].format('MMM D, YYYY HH:mm:ss'));
                // qB.log('start/end');
                // qB.log(start.format('MMM D, YYYY HH:mm:ss'));
                // qB.log(end.format('MMM D, YYYY HH:mm:ss'));
                // qB.log(dateRangeEntry[0].utc(), start.utc());
                // qB.log(dateRangeEntry[1].utc(), end.utc());
                // qB.log('-=-=-=-=-=');
                // qB.log('dateRangeEntry[0].isSame(start)', dateRangeEntry[0].isSame(start));
                // qB.log('dateRangeEntry[1].isSame(start)', dateRangeEntry[1].isSame(end));
                // if (dateRangeEntry[0].isSame(start) && dateRangeEntry[1].isSame(end))
                if (strFnStart === strValStart && strFnEnd === strValEnd)
                    _labelAlias = valueKey;
            });
            // start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');

            // qB.log(true, _labelAlias, _label);

            // show label alias or just label with dates
            $(_dateReport).html(_labelAlias || _label);

            var _eventData = {
                start: start,
                end: end
            };

            Sandbox.eventNotify('widget-datetime-range-changed', _eventData);

            if (_options.events && _options.events.change)
                _options.events.change(null, _eventData);
        }

        // add control element
        var _containerBlockControl = this.getContainerBlock('control');

        var _control = this.getControl('daterange');
        _control.append(this.getTextIcon('calendar'));
        _control.append(_dateReport);
        _control.append(this.getElement('arrow-dn'));

        _containerBlockControl.append(_control);

        // update continer group
        _widget.container.group.append(_containerBlockControl);


        // set startup date range
        var _startupDate = _ranges['Current Month'] || _ranges['Today'];
        if (typeof _options.startupDateRange === 'string' && _ranges[_options.startupDateRange])
            _startupDate = _ranges[_options.startupDateRange];
        if (_.isArray(_options.startupDateRange) && _options.startupDateRange.length == 2)
            _startupDate = _options.startupDateRange;

        $(_control).daterangepicker({
                ranges: _(_ranges).clone(),
                maxDate: moment().utc().endOf('day'),
                startDate: _startupDate[0],
                endDate: _startupDate[1]
            },
            _fnUpdateReportLabel
        );

        _fnUpdateReportLabel.apply(null, _startupDate);

        // attach events
        this.attachEvents(_options.events, _control);
        // if (_options.events)
        //     _(_options.events).each(function(eventFn, eventID){
        //         _control.on(eventID, eventFn);
        //     });

        this.renderIntoContainer(_options, _widget.el);

        return {
            widget: _widget,
            el: _widget.el,
            getControl: function () {
                return _control;
            },
            setValue: function (value) {
                _control.val(value);
            },
            getValue: function (filterFn) {
                if (filterFn)
                    return filterFn(_control.val());
                return _control.val();
            }
        };
    }

    // Options
    //  Name        type            default         description
    //  format      string          'mm/dd/yyyy'    the date format, combination of d, dd, m, mm, yy, yyy.
    //  weekStart   integer         0               day of the week start. 0 for Sunday - 6 for Saturday
    //  viewMode    string|integer  0 = 'days'      set the start view mode. Accepts: 'days', 'months', 'years', 0 for days, 1 for months and 2 for years
    //  minViewMode string|integer  0 = 'days'      set a limit for view mode. Accepts: 'days', 'months', 'years', 0 for days, 1 for months and 2 for years
    //
    // Documentation: http://www.eyecon.ro/bootstrap-datepicker/
    HtmlComponents.prototype.widgetDateTimePicker = function (options) {
        _options = _.extend({
            label: "Date:",
            format: 'mm/dd/yyyy',
            minView: 2,
            todayHighlight: true,
            todayBtn: true
            // autoclose: true
        }, options || {});

        var _icon = this.getTextIcon('calendar');
        var _input = this.getSimpleInput({
            cssClass: "date",
            messages: {
                helper: _options.label,
                afterControlBlock: _icon
            },
            value: _options.value
        })
        var _control = _input.getInput();

        var _widget = this.getWidget('DateTimePicker', _options || {}, this.getContainer());
        var _containerBlockControl = this.getContainerBlock('control');
        _containerBlockControl.append(_input.html);

        _control.datetimepicker(_options);
        _control.mask("99/99/9999");

        _icon.on('click', function () {
            _control.datetimepicker('show');
        });

        // update continer group
        _widget.container.group.append(_containerBlockControl);

        return {
            widget: _widget,
            el: _widget.el,
            html: _widget.el,
            getControl: function () {
                return _control;
            },
            setValue: function (value) {
                _control.val(value);
            },
            getValue: function (filterFn) {
                if (filterFn)
                    return filterFn(_control.val());
                return _control.val();
            }
        };

    }

    HtmlComponents.prototype.widgetArrayLinks = function (config) {

        // widget
        var _widget = this.getWidget('ArrayLinks', config, this.getContainer());
        // var _container = this.getContainer();
        // var _containerGroup = this.getContainerGroup();

        var _links = [];
        var _linksDict = [];

        var self = this;
        if (config.links) 
            _(config.links).each(function (wgtConfig, wgtKey) {
                
                /* put links here */
                // qB.log('ArrayLinks adding new link', wgtConfig);
                var _link = self.getLink(wgtConfig)
                var _containerBlock = self.getContainerBlock('link');

                _links.push(_link);
                _linksDict[wgtKey] = _link;
                _containerBlock.html(_link);
                _widget.container.group.append(_containerBlock);

                // if (wgtConfig.events)
                //     _(wgtConfig.events).each(function(eventFn, eventID){
                //         _link.on(eventID, eventFn);
                //     });

                if (wgtConfig.isActive)
                    $(_link).addClass('render-active');

                _link.on('click', function () {
                    _(_links).each(function(link){
                        $(link).removeClass('render-active');
                    });
                    $(this).addClass('render-active');
                });

            });

        // _container.html(_containerGroup);
        // _widget.html(_container);
        // qB.log(true, 'ArrayLinks >>> ', _widget);
        return {
            widget: _widget,
            el: _widget.el,
            links: _links,
            linksDict: _linksDict,
            getLinksWrapped: function (container) {
                var _wrapped = [];
                _(_links).each(function(link){
                    var _cnt = $(container).clone();
                    _cnt.html(link);
                    _wrapped.push(_cnt);
                });
                return _wrapped;
            }
        }
    }

    HtmlComponents.prototype.widgetArrayLabels = function (config) {

        // widget
        var _widget = this.getWidget('ArrayLabels', config, this.getContainer());
        // var _container = this.getContainer();
        // var _containerGroup = this.getContainerGroup();

        var _labels = [];

        var self = this;
        if (config.labels) 
            _(config.labels).each(function (wgtConfig, wgtKey) {
                
                var _label = self.getLabel(wgtConfig.text)
                var _containerBlock = self.getContainerBlock('label');

                _labels.push(_label);
                _containerBlock.html(_label);
                _widget.container.group.append(_containerBlock);
            });


        return {
            widget: _widget,
            el: _widget.el,
            labels: _labels,
            getLinksWrapped: function (container) {
                var _wrapped = [];
                _(_labels).each(function(label){
                    var _cnt = $(container).clone();
                    _cnt.html(label);
                    _wrapped.push(_cnt);
                });
                return _wrapped;
            }
        }
    }

    HtmlComponents.prototype.widgetPanel = function (config) {

        // control wrapper
        var _widget = this.getWidget('panel', config, this.getContainer(true));
        var _widgets = {};
        var self = this;

        if (config.widgets)
            _(config.widgets).each(function (wgtConfig, wgtKey) {

                // skip unexisted widget fn
                if (typeof self['widget' + wgtKey] !== 'function')
                    return;

                // generate widget
                var _wgt = self['widget' + wgtKey].call(self, wgtConfig);

                _widgets[wgtKey] =_wgt;
                // qB.log(true, _widget.container)
                _widget.container.el.append(_wgt.el);
                _widget.container.el.append(self.getSpacer());
            });

        // render into container
        if (config.container) {
            // qB.log('render into container', _widget.el)
            config.container.html('');
            config.container.append(_widget.el);
        }

        return {
            widget: _widget,
            el: _widget.el,
            widgets: _widgets
        }
    }

    HtmlComponents.prototype.widgetHelpTooltips = function (titleFn) {
        var _self = this;
        $('body').on('click', function (event) {
            var $el = $(event.target);
            if ($el.hasClass(HtmlComponents.CSS_ELEM_ICON)) {
                $('.icon-help').tooltip('destroy');
                $('.tooltip').remove();
                var _tooltipConfig = {
                    placement: "bottom",
                    html: true,
                    trigger: "manual",
                    container: '.page-layout',
                }

                // setup tooltip custom function
                if (titleFn)
                    _tooltipConfig.title = function () {
                        // get last class neme of clicked element (in general it is help icon)
                        var elementCssNames = _self.getCssNames($el).pop();
                        if (!elementCssNames)
                            return false;
                        return titleFn.call($el, elementCssNames);
                    }

                // initialize tooltip
                $el.tooltip(_tooltipConfig).tooltip("toggle");
            } else {
                $('.icon-help').tooltip('destroy');
                $('.tooltip').remove();
            }
        });
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

    HtmlComponents.prototype.explodeTextAndWrap = function (explodeChar, text, options) {
        var textLines = text.split(explodeChar);
        var _wrapper = [];//this.getWrapper();
        var self = this;
        _(textLines).each(function(textLine){
            _wrapper.push(self.getLabel(textLine));
        });
        return _wrapper;
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
        var wnd = this.getModalWindow(message, _dialogOptions);
    }

    /* animations */
    HtmlComponents.prototype.animationPseudoDelete = function (el, callback) {
        var _origBgColor = $(el).css("background-color");
        $(el).css("background-color","#FF3700");
        // $(el).css("background-color","#FF9500");
         
        $(el).fadeOut(400, function(){
            $(el).css("background-color", _origBgColor);
            $(el).fadeIn(100, function(){
                if (typeof callback === "function")
                    callback();
            });
        });
    }
    HtmlComponents.prototype.animationDelete = function (el, callback) {
        $(el).css("background-color","#FF3700");
         
        $(el).fadeOut(400, function(){
            $(el).remove();
            if (typeof callback === "function")
                callback();
        });
    }
    HtmlComponents.prototype.animationAttention = function (el, callback) {
        // var _colorOrig = $(el).css("color");
        $(el).css("color","#FF3700");

        $(el).fadeOut(100).fadeIn(100, function () {
            $(el).attr("style", null);
        });

        // $(el).attr("style", null);

        // $(el).css({"background-color": _bgOrig, opacity: 1});
         
        // $(el).fadeOut(400, function(){
        //     $(el).remove();
        //     callback();
        // });
    }

    // utils
    HtmlComponents.prototype.attachEvents = function (events, _elem) {
        if (events && _elem instanceof $)
            _(events).each(function(eventFn, eventID){
                _elem.on(eventID, eventFn);
            });
    }
    HtmlComponents.prototype.renderIntoContainer = function (cnt, el) {
        // adjust args
        if (!cnt || !el)
            return false;

        var container = null;

        if (cnt instanceof $)
            container = cnt;

        if (cnt.container instanceof $)
            container = cnt.container;

        if (!container)
            return false;

        container.html('').append(el);

        Sandbox.eventNotify('dom:changed', {
            target: container,
            element: el
        });
    }

    HtmlComponents.prototype.getCssDefaultName = function (name) {
        return name || 'default';
    }


    HtmlComponents.prototype.getCssNames = function (element) {
        var className = element && $(element).attr('class');
        if (className)
            return className.split(" ");
        return [];
    }

    HtmlComponents.prototype.getParentElement = function (element) {
        return $(element).parent();
    }

    HtmlComponents.prototype.getControlRelatedLabelByControlID = function (controlID) {
        return $('label[for="' + controlID + '"]');
    }

    HtmlComponents.prototype.getCssScopeArray = function (baseCssName, names) {
        return this.setCssScope(baseCssName, names).split(' ');
    }

    HtmlComponents.prototype.setCssScope = function (baseCssName, names) {

        if (_.isString(names))
            names = this.getCssDefaultName(names).split(" ");
        
        if (!_.isArray(names))
            names = [];

        if (baseCssName)
            return _(names).reduce(function(memo, name) {
                return memo + " " + baseCssName + '-' + name;
            }, baseCssName).toLowerCase();
    }

    HtmlComponents.prototype.elementToggleDisable = function (el, disabled) {
        if (disabled) {
            $(el).addClass(HtmlComponents.CSS_RENDER_DISABLED).attr('disabled', true);
        }
        else
            $(el).removeClass(HtmlComponents.CSS_RENDER_DISABLED).attr('disabled', null);
        return $(el);
    }

    HtmlComponents.prototype.arrayToHtmlList = function (arrayItems, asHtml) {
        var self = this;
        var _list = this.generateTag('ul');
        _(arrayItems).each(function(value) {
            _list.append(self.generateTag('li').html(value));
        });
        if (asHtml)
            return _list.get(0).outerHTML;
        return _list;
    }

    return HtmlComponents;

});