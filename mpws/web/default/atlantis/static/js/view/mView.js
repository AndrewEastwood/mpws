define("default/js/view/mView", [
    'default/js/lib/backbone',
    'default/js/lib/handlebars',
    'default/js/lib/extend.string',
], function (Backbone, Handlebars) {

    var MView = Backbone.View.extend({
        initialize: function (options) {
            // debugger;
            if (options && options.urlOptions && this.model && this.model.updateUrlOptions)
                this.model.updateUrlOptions(options.urlOptions);

            if (options && options.template)
                this.template = options.template;
        },
        isCollectionView: function () {
            return !_.isEmpty(this.collection);
        },
        isModelView: function () {
            return !_.isEmpty(this.model) && !this.isCollectionView();
        },
        fetchAndRender: function (options) {
            var _self = this;
            // debugger;
            if (this.isCollectionView())
                this.collection.fetch({
                    success: function () {
                        _self.render();
                    }
                });
            else if (this.isModelView()) { 
                if (options)
                    this.model.updateUrlOptions(options);

                if (this.model.url)
                    this.model.fetch({
                        success: function () {
                            _self.render();
                        },
                        error: function () {
                            _self.render();
                        }
                    });
                else
                    this.render();
            } else
                this.render();
        },
        render: function () {
            var _self = this;

            // debugger;
            function _innerRenderFn () {

                if (_self.isCollectionView() && _self.itemViewClass) {
                    // if (_self.itemViewClass) {
                        _self.viewItems = _self.viewItems || [];

                        if (_self.viewItems.length)
                            _(this.viewItems).invoke('remove');

                        _self.collection.each(function(model) {
                            var p = new _self.itemViewClass({model: model});
                            _self.viewItems.push(p);
                            _self.$el.append(p.render().el);
                        });
                    // } else
                        // _tplData = _self.collection.toJSON();
                } else {

                    var _tplData = null;
                    if (_self.isCollectionView())
                        _tplData = _self.collection.toJSON();
                    else if (_self.isModelView())
                        _tplData = this.model.toJSON();

                    // debugger;
                    if (typeof this.template === "function")
                        this.$el.html(this.template({
                            app: {
                                config: window.app.config,
                                location: {
                                    fragment: Backbone.history.fragment
                                }
                            },
                            data: _tplData
                        }));

                    if (typeof this.template === "string")
                        this.$el.html(this.template);
                }

                this.trigger('mview:renderComplete', this);
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

    return MView;

});