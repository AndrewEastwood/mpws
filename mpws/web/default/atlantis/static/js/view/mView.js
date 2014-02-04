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
        render: function (options) {
            var _self = this;

            // debugger;
            function _innerRenderFn (error) {
                // debugger;
                if (typeof this.template === "function")
                    this.$el.html(this.template({
                        error: error,
                        app: {
                            config: window.app.config,
                            location: {
                                fragment: Backbone.history.fragment
                            }
                        },
                        options: options,
                        data: this.model && this.model.toJSON()
                    }));

                if (typeof this.template === "string")
                    this.$el.html(this.template);

                if (typeof callback === "function")
                    callback(this);

                this.trigger('mview:render-complete', this);
            }

            function _doRender (error) {
                if (typeof _self.template === "string" && _self.template.has('/hbs!')) {
                    require([_self.template], function (tpl) {
                        // debugger;
                        _self.template = tpl;// Handlebars.compile(tpl);
                        _innerRenderFn.call(_self, error);
                    });
                } else
                    _innerRenderFn.call(_self, error);
            }

            if (options && this.model)
                this.model.updateUrlOptions(options);

            if (this.model && this.model.url)
                this.model.fetch({
                    success: function () {
                        _doRender();
                    },
                    error: function (model, response) {
                        _doRender(response.responseText);
                    }
                });
            else
                _doRender();

            return this;
        }
    });

    return MView;

});