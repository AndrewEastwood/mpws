APP.Modules.register("view/mview", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/storage',
], function (app, Sandbox, $, _, Backbone, Storage) {
    
    var MView = Backbone.View.extend({

        _defaultConfig: {
            isRequiredOnce: false,
            name: false,
            dependencies: [],
            callback: null
        },

        _renderConfig: {},

        viewType: {
            PARTIAL: 'partial',
            HELPER: 'helper'
        },

        viewState: {
            LOADING: 'loading',
            NORMAL: 'normal'
        },

        viewPlacement: {
            REPLACE: 'replace',
            PREPEND: 'prepend',
            APPEND: 'append'
        },

        template: false,

        initialize: function (viewConfig) {
            _.extend(this._renderConfig, this._defaultConfig, viewConfig);
        },

        getConfig: function () {
            return this.options;
        },

        setPlaceholderState: function (placeholder, state, showOrHide) {
            var _conf = this.getConfig();
            var _injectionType = placeholder.placement || _conf.viewPlacement.REPLACE;

            if (placeholder.placement === _conf.viewPlacement.REPLACE) {
                if (state === _conf.viewState.LOADING) {
                    if (showOrHide)
                        placeholder.target.html('').addClass('render-loading');
                    else
                        placeholder.target.removeClass('render-loading');
                }
            }
        },



        render: function () {
            var self = this;
            var _conf = this.getConfig();

            // avoid loading already loaded component
            if (Storage.has(elementKey) && _conf.isRequiredOnce)
                return;
            Storage.add(elementKey, true);


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
                // remove loading state
                self.setPlaceholderState(_pholder, mpwsPage.STATE.LOADING, false);
                // tell Async lib that we've completed rendering
                callback.apply(null, _args);
                // call user's callback
                if (_.isFunction(renderElementOptions.callback))
                    renderElementOptions.callback.apply(null, _args);
                // global notify
                Sandbox.eventNotify('mpws:page:render-complete', {
                    component: elementKey
                });
            };
            // setup data
            var _dataFn = function (callbackData) {
                var _dataSrc = renderElementOptions.data.source;
                var _dataParams = renderElementOptions.data.params || {};
                if (typeof _dataSrc === "string")
                    $.post(_dataSrc, _dataParams, callbackData, "json");
                else if (_.isFunction(_dataSrc)) {
                    if (_.isEmpty(_dataParams))
                        _dataSrc.call(null, callbackData);
                    else
                        _dataSrc.call(null, _dataParams, callbackData);
                }
            }

            // show loading state
            self.setPlaceholderState(_pholder, mpwsPage.STATE.LOADING, true);

            if (_deps)
                self.setupDependencies(_deps, function () {
                    _renderFn(_tpl, _dataFn, _pholder, _cb);
                });
            else
                _renderFn(_tpl, _dataFn, _pholder, _cb);


        }
    });

    return
});