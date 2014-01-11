// it is the base page router

APP.Modules.register("lib/mpws.page", [], [

    /* import dep packages */
    'lib/jquery',
    'lib/underscore',
    'lib/templateEngine',
    'lib/async',
    // 'lib/storage',
    'lib/utils',
    // 'lib/jquery_ui',

    /* component implementation */
], function (app, Sandbox, $, _, tplEngine, AsyncLib) {

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
        return mpwsPage;
    }

    // register template helpers
    mpwsPage.RegisterHelper = function(key, helper) {
        tplEngine.registerHelper(key, helper);
        return mpwsPage;
    }

    mpwsPage.getPageError = function() {
        mpwsPage.getPageBody('Woohoo!!! Error 404');
    }

    mpwsPage.getPagePlaceholders = function() {
        return {
            header : $('.MPWSPageHeader'),
            body : $('.MPWSPageBody'),
            footer : $('.MPWSPageFooter')
        };
    }

    mpwsPage.getPageBody = function (content, clear) {
        var _body = mpwsPage.getPagePlaceholders().body;
        if (clear)
            $(_body).html('');
        if (content) {
            // stop loading animation
            mpwsPage.pageSetState(mpwsPage.STATE.LOADING, false);
            // append content
            $(_body).append(content);
        }
        return _body;
    }

    mpwsPage.getPageHeader = function () {
        return mpwsPage.getPagePlaceholders().header;
    }

    mpwsPage.getPageFooter = function () {
        return mpwsPage.getPagePlaceholders().footer;
    }

    mpwsPage.setPageState = function (state, showOrHide) {
        if (state === mpwsPage.STATE.LOADING) {
            if (showOrHide)
                mpwsPage.getPagePlaceholders().body.html('').addClass('render-loading');
            else
                mpwsPage.getPagePlaceholders().body.removeClass('render-loading');
        }
    }

    mpwsPage.setPlaceholderState = function (placeholder, state, showOrHide) {
        var _injectionType = placeholder.placement || mpwsPage.PLACEMENT.REPLACE;
        if (placeholder.placement === mpwsPage.PLACEMENT.REPLACE) {
            if (state === mpwsPage.STATE.LOADING) {
                if (showOrHide)
                    placeholder.container.html('').addClass('render-loading');
                else
                    placeholder.container.removeClass('render-loading');
            }
        }
    }

    mpwsPage.pageName = function (name) {
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
    mpwsPage.registerPartial = function(key, partial) {
        mpwsPage.RegisterPartial(key, partial);
        return mpwsPage;
    }

    // register template helpers
    mpwsPage.registerHelper = function(key, helper) {
        mpwsPage.RegisterHelper(key, helper);
        return mpwsPage;
    }


    function _setupDependenciesFn (deps, callback) {
        var _dataMap = {};
        // var _self = this;

        if (!deps)
            return callback(null);

        for (var templateAccessKey in deps)
            (function (key, depItem) {
                _dataMap[key] = function (callback) {
                    mpwsPage.getTemplate(depItem.url, false, function (err, templateHtml) {
                        callback(err, _.extend({}, depItem, {
                            template: templateHtml
                        }));
                    });
                }
            })(templateAccessKey, deps[templateAccessKey]);

        AsyncLib.parallel(_dataMap, function(err, results) {

            // app.log(true, "mpwsPage.setupDependencies", err, results);

            _(results).each(function(depItem, key) {
                if (depItem.type === 'partial')
                    mpwsPage.registerPartial(key, depItem.template);
                if (depItem.type === 'helper' && _.isFunction(depItem.fn))
                    mpwsPage.registerHelper(key, depItem.fn);
            });

            callback(err, results);
        });
    }

    mpwsPage.getTemplate = function(templateUrl, deps, callback) {
        var _fetchTemplateFn = function (err) {
            // just fire callback
            if (!templateUrl && _.isFunction(callback))
                return callback(err, null);

            if (tplEngine.hasTemplate(templateUrl))
                callback(err, tplEngine.getTemplate(templateUrl));
            else
                mpwsPage.requestTemplate(templateUrl, function (templateHtml) {
                    tplEngine.setTemplate(templateUrl, templateHtml);
                    callback(err, templateHtml);
                });
        };

        if (deps)
            _setupDependenciesFn(deps, _fetchTemplateFn);
        else
            _fetchTemplateFn(null);
    }

    mpwsPage.requestTemplate = function (templatePath, callback) {
        var _config = app.Page.getConfiguration();
        templatePath = templatePath.replace(/\./g, '//').replace('@', '.');
        $.get(_config.URL.staticUrlBase + templatePath).success(callback);
    }

    // mpwsPage.prototype.createRenderPlacement = function(target, placement) {

    //     if (target.target || target.placement)
    //         return this.createRenderPlacement(target.target, target.placement);

    //     return {
    //         target: target || mpwsPageLib.getPageBody(),
    //         placement: placement || mpwsPage.PLACEMENT.REPLACE
    //     };
    // }

    // mpwsPage.prototype.createRenderConfig = function(name, options, collection) {
    //     var self = this;

    //     var placeholder = null;
    //     if (options.placeholder)
    //         placeholder = self.createRenderPlacement(options.placeholder.target, options.placeholder.placement);
    //     else
    //         placeholder = self.createRenderPlacement(); // default placeholder

    //     var _renderConfig = {
    //         isRequiredOnce: options.isRequiredOnce || false,
    //         name: name,
    //         data: options.data || {},
    //         template: options.template || false,
    //         dependencies: options.dependencies || [],
    //         placeholder: placeholder,
    //         callback: options.callback || null
    //     };

    //     if (collection)
    //         collection[name] = _renderConfig;

    //     var entry = {};
    //     entry[name] = _renderConfig;

    //     return entry;
    // }

    // mpwsPage.prototype.modifyRenderConfig = function(name, base, modified) {
    //     if (name && base && !modified) {
    //         modified = base;
    //         base = name;
    //         name = base.name || modified.name || "default";
    //     }
    //     return this.createRenderConfig(base.name, _.extend({}, base, modified));
    // }

//     mpwsPage.render = function(renderItemConfig) {
//         // start loadng animation
//         // this.setPageState(mpwsPage.STATE.LOADING, true);

//         // var _renderOptionsList = [].slice.apply(arguments);
//         // var options = _renderOptionsList.unshift();
//         // _(_renderOptionsList).each(function(optionEntry){
//         //     renderItems = _.extend({}, renderItems, optionEntry);
//         // });


//         // app.log(true, 'render options:', renderItemConfig, arguments);

//         var self = mpwsPage;
//         var _renderCommands = {};

//         // will setup / fetch and store page components
//         // @deps - json opject where each item must contain the 
//         //         following fields:
//         //              url
//         //              type: [partial|helper]
//         //              fn: (when type is helper)
//         var _setupDependenciesFn = function(deps, callback) {
//             var _dataMap = {};
//             // var _self = this;

//             if (!deps)
//                 return callback(null);

//             for (var templateAccessKey in deps)
//                 (function (key, depItem) {
//                     _dataMap[key] = function (callback) {
//                         self.getTemplate(depItem.url, function (err, templateHtml) {
//                             callback(err, _.extend({}, depItem, {
//                                 template: templateHtml
//                             }));
//                         });
//                     }
//                 })(templateAccessKey, deps[templateAccessKey]);

//             AsyncLib.parallel(_dataMap, function(err, results) {

//                 // app.log(true, "mpwsPage.setupDependencies", err, results);

//                 _(results).each(function(depItem, key) {
//                     if (depItem.type === 'partial')
//                         self.registerPartial(key, depItem.template);
//                     if (depItem.type === 'helper' && _.isFunction(depItem.fn))
//                         self.registerHelper(key, depItem.fn);
//                 });

//                 callback(err, results);
//             });
//         }

//         var _renderFn = function (templatePath, templateDataReceiverFn, placeholder, callback) {
//             var _injectionFn = function (error, template, data) {
//                 var html = false;
//                 // [4] combine everything together
//                 if (template) {
//                     var templateFn = tplEngine.compile(template);
//                     // compose template data
//                     var _tplData = {
//                         app: {
//                             test: 'test',
//                             config: app.Page.getConfiguration(),
//                             location: {
//                                 fragment: Backbone.history.fragment
//                             }
//                         },
//                         source: data || {}
//                     }
//                     // app.log(true, 'template data is', _tplData);
//                     html = templateFn(_tplData);
//                 }
//                 // render into placeholder
//                 if (placeholder && placeholder.target) {
//                     var _injectionType = placeholder.placement || mpwsPage.PLACEMENT.REPLACE;
//                     // remove previous dom element
//                     var _elementID = $(html).filter('*').first().attr('id');
//                     if (!_.isEmpty(_elementID))
//                         $(placeholder.target).find(_elementID.asCssID()).remove();
//                     // add new element
//                     if (_injectionType == mpwsPage.PLACEMENT.REPLACE)
//                         $(placeholder.target).html(html);
//                     else if (_injectionType == mpwsPage.PLACEMENT.PREPEND)
//                         $(placeholder.target).prepend(html);
//                     else if (_injectionType == mpwsPage.PLACEMENT.APPEND)
//                         $(placeholder.target).append(html);
//                 }
//                 // perform callback
//                 if (_.isFunction(callback))
//                     callback(error, html, _tplData, placeholder);
//             }

//             var _templateReceiverFn = function (errorTpl, template) {
//                 // [3] call data receiver
//                 if (_.isFunction(templateDataReceiverFn))
//                     templateDataReceiverFn(function (errorData, data) {
//                         // // app.log(true, 'data received', data);
//                         _injectionFn(errorTpl || errorData, template, data);
//                     });
//                 else
//                     _injectionFn(errorTpl, template);
//             }

//             // [2] load main template and finally/or just call _templateReceiverFn
//             self.getTemplate(templatePath, _templateReceiverFn);
//         }

//         // transform config
//         var renderElementOptions = renderItemConfig;
//         var elementKey = renderItemConfig.name;

//         // _(renderItems).each(function (renderElementOptions, elementKey) {

//             // avoid loading already loaded component
//             if (Storage.has(elementKey) && renderElementOptions.isRequiredOnce)
//                 return;
//             Storage.add(elementKey, true);

// //             app.log(true, elementKey, Storage.getAll(), Storage.has(elementKey), renderElementOptions, renderElementOptions.isRequiredOnce);

//             _renderCommands[elementKey] = function (callback) {
//                 // template (what render)
//                 var _tpl = renderElementOptions.template;
//                 // get placeholder (where to show)
//                 var _pholder = renderElementOptions.placeholder;
//                 // get deps (when our template has some deps weh ave to download them first)
//                 var _deps = renderElementOptions.dependencies;
//                 // setup callback
//                 var _cb = function (/* rendderFn args are here */) {
//                     var _args = [].slice.apply(arguments);
//                     // adjusust args
//                     _args = _.isEmpty(_args) ? [null, true] : _args;
//                     // remove loading state
//                     self.setPlaceholderState(_pholder, mpwsPage.STATE.LOADING, false);
//                     // tell Async lib that we've completed rendering
//                     callback.apply(null, _args);
//                     // call user's callback
//                     if (_.isFunction(renderElementOptions.callback))
//                         renderElementOptions.callback.apply(null, _args);
//                     // global notify
//                     Sandbox.eventNotify('mpws:page:render-complete', {
//                         component: elementKey
//                     });
//                 };
//                 // // setup data
//                 // var _dataFn = function (callbackData) {
//                 //     var _dataSrc = renderElementOptions.data.source;
//                 //     var _dataParams = renderElementOptions.data.params || {};
//                 //     if (typeof _dataSrc === "string")
//                 //         $.post(_dataSrc, _dataParams, callbackData, "json");
//                 //     else if (_.isFunction(_dataSrc)) {
//                 //         if (_.isEmpty(_dataParams))
//                 //             _dataSrc.call(null, callbackData);
//                 //         else
//                 //             _dataSrc.call(null, _dataParams, callbackData);
//                 //     }
//                 // }

//                 // show loading state
//                 // self.setPlaceholderState(_pholder, mpwsPage.STATE.LOADING, true);

//                 if (_deps)
//                     _setupDependenciesFn(_deps, function () {
//                         _renderFn(_tpl, _pholder, _cb);
//                     });
//                 else
//                     _renderFn(_tpl, _pholder, _cb);
//             }
//         // });

//         AsyncLib.parallel(_renderCommands, function (error, data) {
//             // global notify
//             Sandbox.eventNotify('mpws:page:render-complete-all', data);
//         });
//     };

    return mpwsPage;

});