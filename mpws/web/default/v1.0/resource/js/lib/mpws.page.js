// it is the base page router

APP.Modules.register("lib/mpws.page", [
    /* import globals */
    window

], [

    /* import dep packages */
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/templateEngine',
    'lib/async'
    // 'lib/jquery_ui',

    /* component implementation */
], function (wnd, app, Sandbox, $, _, Backbone, mpwsAPI, tplEngine, AsyncLib) {

    function mpwsPage () {}

    mpwsPage.TYPE = {
        PARTIAL: 'partial',
        HELPER: 'helper',
    };

    mpwsPage.STATE = {
        LOADING: 'loading'
    };

    mpwsPage.PLACEMENT = {
        REPLACE: 'replace',
        PREPEND: 'prepend',
        APPEND: 'append'
    };

    mpwsPage.RegisterPartial = function(key, partial) {
        tplEngine.registerPartial(key, partial);
        return this;
    }

    // register template helpers
    mpwsPage.RegisterHelper = function(key, helper) {
        tplEngine.registerHelper(key, helper);
        return this;
    }

    mpwsPage.prototype.getPageError = function() {
        this.getPageBody('Woohoo!!! Error 404');
    }

    mpwsPage.prototype.getPagePlaceholders = function() {
        return {
            header : $('.MPWSPageHeader'),
            body : $('.MPWSPageBody'),
            footer : $('.MPWSPageFooter')
        };
    }

    mpwsPage.prototype.getPageBody = function (content, clear) {
        var _body = this.getPagePlaceholders().body;
        if (clear)
            $(_body).html('');
        if (content) {
            // stop loading animation
            this.pageSetState(mpwsPage.STATE.LOADING, false);
            // append content
            $(_body).append(content);
        }
        return _body;
    }

    mpwsPage.prototype.getPageHeader = function () {
        return this.getPagePlaceholders().header;
    }

    mpwsPage.prototype.getPageFooter = function () {
        return this.getPagePlaceholders().footer;
    }

    mpwsPage.prototype.pageSetState = function (state, showOrHide) {
        if (state === mpwsPage.STATE.LOADING) {
            if (showOrHide)
                this.getPagePlaceholders().body.html('').addClass('render-loading');
            else
                this.getPagePlaceholders().body.removeClass('render-loading');
        }
    }

    mpwsPage.prototype.pageName = function (name) {
        var classNames = $('body').attr('class');

        if (classNames)
            classNames = classNames.split(' ');
        
        // remove all custom page names
        for (var key in classNames)
            if (/^MPWSPage_/.test(classNames[key]))
                $('body').removeClass(classNames[key]);

        $('body').addClass('MPWSPage_' + name || 'default');
    }

    // register partial reusable object 
    mpwsPage.prototype.registerPartial = function(key, partial) {
        mpwsPage.RegisterPartial(key, partial);
        return this;
    }

    // register template helpers
    mpwsPage.prototype.registerHelper = function(key, helper) {
        mpwsPage.RegisterHelper(key, helper);
        return this;
    }

    mpwsPage.prototype.getTemplate = function(templateUrl, callback) {
        // just fire callback
        if (!templateUrl && _.isFunction(callback))
            return callback(null, null);

        if (tplEngine.hasTemplate(templateUrl))
            callback(null, tplEngine.getTemplate(templateUrl));
        else
            mpwsAPI.requestTemplate(templateUrl, function (templateHtml) {
                tplEngine.setTemplate(templateUrl, templateHtml);
                callback(null, templateHtml);
            });
    }

    // will setup / fetch and store page components
    // @deps - json opject where each item must contain the 
    //         following fields:
    //              url
    //              type: [partial|helper]
    //              fn: (when type is helper)
    mpwsPage.prototype.setupDependencies = function(deps, callback) {
        var _dataMap = {};
        var _self = this;

        if (!deps)
            return callback(null);

        for (var templateAccessKey in deps)
            (function (key, depItem) {
                _dataMap[key] = function (callback) {
                    _self.getTemplate(depItem.url, function (err, templateHtml) {
                        callback(err, _.extend({}, depItem, {
                            template: templateHtml
                        }));
                    });
                }
            })(templateAccessKey, deps[templateAccessKey]);

        AsyncLib.parallel(_dataMap, function(err, results) {

            app.log(true, "mpwsPage.setupDependencies", err, results);

            _(results).each(function(depItem, key) {
                if (depItem.type === 'partial')
                    _self.registerPartial(key, depItem.template);
                if (depItem.type === 'helper' && _.isFunction(depItem.fn))
                    _self.registerHelper(key, depItem.fn);
            });

            callback(err, results);
        });
    }

    mpwsPage.prototype.render = function(options) {

        var self = this;
        var _renderCommands = {};

        var _renderFn = function (templatePath, templateDataReceiverFn, placeholder, callback) {
            var _injectionFn = function (error, template, data) {
                var html = false;
                // [4] combine everything together
                if (template) {
                    var templateFn = tplEngine.compile(template);
                    // compose template data
                    var _tplData = {
                        app: {
                            config: app.Page.getConfiguration()
                        },
                        source: data || {}
                    }
                    app.log(true, 'template data is', _tplData);
                    html = templateFn(_tplData);
                }
                // render into placeholder
                if (placeholder && placeholder.target) {
                    var _injectionType = placeholder.placement || mpwsPage.PLACEMENT.REPLACE;
                    app.log(true, 'injection time', _injectionType);
                    if (_injectionType == mpwsPage.PLACEMENT.REPLACE)
                        $(placeholder.target).html(html);
                    else if (_injectionType == mpwsPage.PLACEMENT.PREPEND)
                        $(placeholder.target).prepend(html);
                    else if (_injectionType == mpwsPage.PLACEMENT.APPEND)
                        $(placeholder.target).append(html);
                }
                // perform callback
                if (_.isFunction(callback))
                    callback(error, html, _tplData, placeholder);
            }

            var _templateReceiverFn = function (errorTpl, template) {
                // [3] call data receiver
                if (_.isFunction(templateDataReceiverFn))
                    templateDataReceiverFn(function (errorData, data) {
                        // app.log(true, 'data received', data);
                        _injectionFn(errorTpl || errorData, template, data);
                    });
                else
                    _injectionFn(errorTpl, template);
            }

            // [2] load main template and finally/or just call _templateReceiverFn
            self.getTemplate(templatePath, _templateReceiverFn);
        }

        // transform config
        _(options).each(function (renderElementOptions, elementKey) {
            _renderCommands[elementKey] = function (callback) {
                // template (what render)
                var _tpl = renderElementOptions.template;
                // get placeholder (where to show)
                var _pholder = renderElementOptions.placeholder;
                // get deps (when our template has some deps weh ave to download them first)
                var _deps = renderElementOptions.dependencies;
                // setup callback
                var _cb = function (/* rendderFn args are here */) {
                    var _args = [].slice.apply(arguments);
                    // adjusust args
                    _args = _.isEmpty(_args) ? [null, true] : _args;
                    // tell Async lib that we've completed rendering
                    callback.apply(null, _args);
                    // call user's callback
                    if (_.isFunction(renderElementOptions.callback))
                        renderElementOptions.callback.apply(null, _args);
                };
                // setup data
                var _dataFn = function (callbackData) {
                    var _dataSrc = renderElementOptions.data.source;
                    var _dataParams = renderElementOptions.data.params || {};
                    if (typeof _dataSrc === "string")
                        $.post(_dataSrc, _dataParams, callbackData, "json");
                    else if (_.isFunction(_dataSrc))
                        _dataSrc.call(null, callbackData);
                }

                if (_deps)
                    self.setupDependencies(_deps, function () {
                        _renderFn(_tpl, _dataFn, _pholder, _cb);
                    });
                else
                    _renderFn(_tpl, _dataFn, _pholder, _cb);
            }
        });

        AsyncLib.parallel(_renderCommands);
    };

    return mpwsPage;

});