define("default/js/view/mView", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/handlebars',
    'default/js/lib/extend.string',
], function (Sandbox, _, Backbone, Handlebars) {

    function _factory () {
        var MView = Backbone.View.extend({
            initialize: function (options) {
                // debugger;
                if (options && options.urlOptions && this.model && this.model.updateUrl)
                    this.model.updateUrl(options.urlOptions);

                if (options && options.template)
                    this.template = options.template;
            },
            isCollectionView: function () {
                return !_.isEmpty(this.collection);
            },
            isModelView: function () {
                return !_.isEmpty(this.model) && !this.isCollectionView();
            },
            fetchAndRender: function (options, fetchOptions) {
                // debugger;
                var _self = this;
                // debugger;
                if (this.isCollectionView()) {
                    if (options)
                        this.collection.updateUrl(options);

                    if (this.collection.url) {
                        // $.xhrPool.abortAll();
                        this.collection.fetch(_.extend({}, fetchOptions || {}, {
                            success: function () {
                                // debugger;
                                _self.render.call(_self);
                            },
                            error: function () {
                                // debugger;
                                // _self.collection.reset();
                                _self.render.call(_self);
                            }
                        }));
                    } else
                        this.render.call(_self);
                } else if (this.isModelView()) {
                    if (options)
                        this.model.updateUrl(options);

                    // debugger;
                    if (this.model.url) {
                        // $.xhrPool.abortAll();
                        this.model.fetch({
                            success: function () {
                                // debugger;
                                _self.render.call(_self);
                            },
                            error: function () {
                                // debugger;
                                // _self.model.clear({silent: true});
                                _self.render.call(_self);
                            }
                        });
                        // this.model.resetUrlOptions();
                    } else
                        this.render.call(_self);
                } else
                    this.render.call(_self);
            },
            render: function () {
                var _self = this;

                // debugger;s
                function _innerRenderFn () {

                    // if (_self.itemViewClass) {
                    _self.displayItems = _self.displayItems || [];

                    if (_self.isCollectionView() && _self.itemViewClass) {
                            // debugger;
                            _self.viewItems = _self.viewItems || [];

                            // remove/clear previous items and views
                            if (_self.viewItems.length)
                                _(this.viewItems).invoke('remove');
                            _self.displayItems = []

                            _self.collection.each(function(model) {
                                // debugger;
                                var p = new _self.itemViewClass({model: model});
                                _self.viewItems.push(p);
                                _self.displayItems.push(p.render().$el.get(0));
                            });
                        // } else
                            // _tplData = _self.collection.toJSON();
                    }

                    // debugger;

                    if (_self.autoRender)
                        _(_self.displayItems).each(function (itemView){
                            // debugger;
                            _self.$el.append(itemView);
                        });
                    else {
                        // debugger;
                        var _tplData = null;
                        var _tplUrlOptions = null;
                        var _tplExtras = null;
                        if (_self.isCollectionView()) {
                            _tplData = _self.collection.toJSON();
                            _tplUrlOptions = _self.collection.getUrlOptions();
                            _tplExtras = _self.collection.getExtras();
                        } else if (_self.isModelView()) {
                            _tplData = this.model.toJSON();
                            _tplUrlOptions = this.model.getUrlOptions();
                            _tplExtras = _self.model.getExtras();
                        }

                        if (typeof this.template === "function") {
                            var Site = require('customer/js/site');
                            this.$el.html(this.template({
                                lang: _self.lang || {},
                                options: Site.options || {},
                                plugins: Site.plugins,
                                location: Backbone.history.location,
                                app: {
                                    config: window.app.config,
                                    location: {
                                        fragment: Backbone.history.fragment
                                    }
                                },
                                data: _tplData,
                                extras: _tplExtras,
                                displayItems: _self.displayItems,
                                urlOptions: _tplUrlOptions
                            }));
                        }

                        if (typeof this.template === "string")
                            this.$el.html(this.template);
                    }

                    if (_self.viewName)
                        Sandbox.eventNotify('view:'+_self.viewName, _self);

                    this.trigger('mview:renderComplete', _self);
                }

                if (typeof this.template === "string" && this.template.has('/hbs!')) {
                    require([this.template], function (tpl) {
                        // debugger;
                        _self.template = tpl;// Handlebars.compile(tpl);
                        _innerRenderFn.call(_self);
                    });
                } else
                    _innerRenderFn.call(_self);

                return this;
            }
        });

        MView.getNew = _factory;

        return MView;
    }

    return _factory();

});