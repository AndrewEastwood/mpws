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
    }

    mpwsPage.STATE = {
        LOADING: 'loading'
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
        tplEngine.registerPartial(key, partial);
        return this;
    }

    // register template helpers
    mpwsPage.prototype.registerHelper = function(key, helper) {
        tplEngine.registerHelper(key, helper);
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

    mpwsPage.prototype.render = function (templatePath, deps, templateDataReceiverFn, options) {
        var self = this;
        // start loadng animation
        this.pageSetState(mpwsPage.STATE.LOADING, true);

        // adjust arguments
        if (_.isFunction(deps)) {
            options = templateDataReceiverFn;
            templateDataReceiverFn = deps;
            deps = null;
        }

        //  && !_.isFunction(templateDataReceiverFn) && _.isUndefined(options)

        // [1] load deps
        self.setupDependencies(deps, function (err) {

            var _injectionFn = function (template, data) {
                var html = false;
                // [4] combine everything together
                if (template) {
                    var templateFn = tplEngine.compile(template);
                    html = templateFn(data);
                }
                if (options && options.el)
                    $(options.el).html(html);
                else
                    self.getPageBody(html, true);
            }

            var _templateReceiverFn = function (error, template) {
                // [3] call data receiver
                if (_.isFunction(templateDataReceiverFn))
                    templateDataReceiverFn(function (error, data) {
                        _injectionFn(template, data);
                    });
                else
                    _injectionFn(template);
            }

            // [2] load main template and finally/or just call _templateReceiverFn
            self.getTemplate(templatePath, _templateReceiverFn);

        })
    }


    return mpwsPage;

});