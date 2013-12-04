APP.Modules.register("view/mview", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.page'
], function (app, Sandbox, $, _, Backbone, mpwsPage) {
    
    var MView = Backbone.View.extend({

        name: "default",

        isRequiredOnce: false,

        dependencies: {},

        template: false,

        placement: mpwsPage.PLACEMENT.REPLACE,

        $el: mpwsPage.getPageBody(),

        initialize: function(cfg) {
            app.log(true, 'cfg', cfg);
        //     this.model.on('change',this.render,this);
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
                name: this.name,
                data: {},
                template: this.template ,
                dependencies: this.dependencies,
                placement: this.placement,
                container: this.$el
                // placeholder: this.placeholder,
            };
        },

        render: function () {
            app.log(true, 'MView', this.model);
            mpwsPage.render(this.getRenderConfig());
            return this;
        }
    });

    // function _createRenderPlacement (target, placement) {

    //     if (target.target || target.placement)
    //         return _createRenderPlacement(target.target, target.placement);

    //     return {
    //         target: target || mpwsPage.getPageBody(),
    //         placement: placement || mpwsPage.PLACEMENT.REPLACE
    //     };
    // }

    // function _createRenderConfig (name, options) {
    //     var self = this;

    //     var placeholder = null;
    //     if (options.placeholder)
    //         placeholder = _createRenderPlacement(options.placeholder.target, options.placeholder.placement);
    //     else
    //         placeholder = _createRenderPlacement(); // default placeholder

    //     var _renderConfig = {
    //         isRequiredOnce: options.isRequiredOnce || false,
    //         name: name,
    //         data: options.data || {},
    //         template: options.template || false,
    //         dependencies: options.dependencies || [],
    //         placeholder: placeholder,
    //         callback: options.callback || null
    //     };

    //     return _renderConfig;

    //     if (collection)
    //         collection[name] = _renderConfig;

    //     var entry = {};
    //     entry[name] = _renderConfig;

    //     return entry;
    // }

    return MView;
});