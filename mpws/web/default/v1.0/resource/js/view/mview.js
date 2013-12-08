APP.Modules.register("view/mview", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.page',
    'lib/storage',
    'lib/templateEngine',
], function (app, Sandbox, $, _, Backbone, mpwsPage, Storage, tplEngine) {

    var _config = app.Page.getConfiguration();

    var MView = Backbone.View.extend({

        // el: mpwsPage.getPageBody(),

        template: false,
        // _options: {

        name: "default",

        isRequiredOnce: false,

        dependencies: {},

        placement: mpwsPage.PLACEMENT.REPLACE,
        // },

        initialize: function(options) {
            // app.log(true ,'view MView initialize', this.model);
            // this.$el = null;
            // this.options = _.extend({}, this._options, options);
        //     this.model.on('change',this.render,this);
            this.$el = $(options.el || this.el);

            this.model.on('mmodel:newdata', function (data) {
                // app.log(true ,'MView on change:data: so new data is available', data);
                _render.call(this, data);
            }, this);
        },
        // initialize: function (viewConfig) {
        //     // var _defaultConfig = {
        //     //     name: this.name,
        //     //     template: this.template,
        //     //     isRequiredOnce: false,
        //     //     dependencies: [],
        //     //     callback: null
        //     // };
        // },

        getRenderConfig: function () {
            return {
                isRequiredOnce: this.isRequiredOnce,
                template: this.template,
                dependencies: this.dependencies,
                placeholder: {
                    placement: this.placement,
                    container: this.$el
                }
                // placeholder: this.placeholder,
            };
        },

        render: function () {

            var _renderConfig = this.getRenderConfig();
            var placeholder = _renderConfig.placeholder;
            mpwsPage.setPlaceholderState(placeholder, mpwsPage.STATE.LOADING, true);

            // app.log(true, 'MView called render function. The "fetch" function is being called')
            this.model.fetch();
            // app.log('render config is ', _renderConfig);
            // mpwsPage.render(this.getRenderConfig());

            // this.$el.html(this.template(this.model.attributes));
            return this;
        }
    });

    function _render (data) {
        // app.log(true, 'view MView render is called', this.$el);
        // avoid loading already loaded component
        if (Storage.has(this.name) && this.isRequiredOnce)
            return;

        Storage.add(this.name, true);

        var _renderConfig = this.getRenderConfig();
        var placeholder = _renderConfig.placeholder;
        var _self = this;

        mpwsPage.getTemplate(_renderConfig.template, _renderConfig.dependencies, function (error, template) {

            var html = false;
            // [4] combine everything together
            if (template) {
                var templateFn = tplEngine.compile(template);
                // compose template data
                var _tplData = {
                    app: {
                        test: 'test',
                        config: _config,
                        location: {
                            fragment: Backbone.history.fragment
                        }
                    },
                    source: data || {}
                }
                // app.log(true, 'template data is', _tplData);
                html = templateFn(_tplData);
            }
            // stop ajax animation
            mpwsPage.setPlaceholderState(placeholder, mpwsPage.STATE.LOADING, false);
            // render into placeholder
            if (placeholder && placeholder.container) {
                var _injectionType = placeholder.placement || mpwsPage.PLACEMENT.REPLACE;
                // remove previous dom element
                var _elementID = $(html).filter('*').first().attr('id');
                if (!_.isEmpty(_elementID))
                    $(placeholder.container).find(_elementID.asCssID()).remove();
                // add new element
                if (_injectionType == mpwsPage.PLACEMENT.REPLACE)
                    $(placeholder.container).html(html);
                else if (_injectionType == mpwsPage.PLACEMENT.PREPEND)
                    $(placeholder.container).prepend(html);
                else if (_injectionType == mpwsPage.PLACEMENT.APPEND)
                    $(placeholder.container).append(html);
            }
            $(placeholder.container).trigger('mview:rendered', [_self.name]);
        });
    }

    return MView;
});