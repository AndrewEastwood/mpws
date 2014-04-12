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
                        // console.log('tpl data', _self.className, _self.getTemplateData.call(_self));
                        if (typeof this.template === "function")
                            this.$el.html(this.template(_self.getTemplateData.call(_self)));

                        if (typeof this.template === "string")
                            this.$el.html(this.template);
                    }

                    if (_self.viewName)
                        Sandbox.eventNotify('view:'+_self.viewName, _self);

                    // notify view that render is done
                    this.trigger('mview:renderComplete', _self);

                    // notify all subscribers when render is completed
                    if (_self.notifyWhenRenderComplete) {
                        // debugger;
                        if (_.isString(_self.notifyWhenRenderComplete))
                            Sandbox.eventNotify(_self.notifyWhenRenderComplete, _self);
                        else if (_.isArray(_self.notifyWhenRenderComplete))
                            _(_self.notifyWhenRenderComplete).each(function(eventName){
                                Sandbox.eventNotify(eventName, _self);
                            });
                    }
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
            },
            getTemplateData: function () {
                var Site = require('customer/js/site');
                var _tplData = null;
                var _tplUrlOptions = null;
                var _tplExtras = null;
                if (this.isCollectionView()) {
                    _tplData = this.collection.toJSON();
                    _tplUrlOptions = this.collection.getUrlOptions && this.collection.getUrlOptions();
                    _tplExtras = this.collection.getExtras && this.collection.getExtras();
                } else if (this.isModelView()) {
                    _tplData = this.model.toJSON();
                    _tplUrlOptions = this.model.getUrlOptions && this.model.getUrlOptions();
                    _tplExtras = this.model.getExtras && this.model.getExtras();
                }
                return {
                    lang: this.lang || {},
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
                    displayItems: this.displayItems,
                    urlOptions: _tplUrlOptions
                }
            }
        });

        MView.getNew = _factory;

        return MView;
    }

    return _factory();

});