define("default/js/view/mview", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/mpws.page',
    'default/js/lib/templateEngine',
], function ($, _, Backbone, mpwsPage, tplEngine) {

    var _config = {}; // application config

    var MView = Backbone.View.extend({

        template: false,

        name: "default",

        dependencies: [],

        subViews: {},

        initialize: function(options) {
            // app.log(true ,'view MView initialize', this.model);
            // var _self = this;
            // debugger;

            if (this.model) {
                this.model.on('change', this.render);
                this.model.fetch();
            }

            // this.model.on('mmodel:newdata', function () {
            //     // app.log(true ,'MView on change:data: so new data is available', data);
            //     _render.call(_self);
            // }, this);
        },

        render: function () {
            // mpwsPage.setElementState(placeholder, 'loading', true);
            // this.model.fetch();

            debugger;

            var _tplToRequest = [this.template];

            _(this.dependencies).each(function(depItem){
                _tplToRequest.push(depItem.url);
            });

            // pass template to plugin that will download them
            _(_tplToRequest).map(function(tplUrl){
                tplUrl = 'default/js/plugin/text!' + tplUrl;
            });

            require(_tplToRequest, function () {
                debugger;

                var _tpl = [].splice.call(arguments);
                var _deps = [].splice.call(arguments, 1);

                // register deps or check wheter we have them registered
                // like: if tplEngine.hasPartial

            });


            return this;
        }

    });

    function _render () {
        // var data = this.model.getTemplateData();
        // debugger;
        // // app.log(true, 'view MView render is called', this.$el);
        // // avoid loading already loaded component
        // // if (Storage.has(this.name) && this.isRequiredOnce)
        // //     return;

        // // Storage.add(this.name, true);

        // // var _renderConfig = this.getRenderConfig();
        // // var placeholder = _renderConfig.placeholder;
        // var _self = this;

        // var _tplToRequest = [this.template];

        // _(this.dependencies).each(function(depItem){
        //     _tplToRequest.push(depItem.url);
        // });

        // // pass template to plugin that will download them
        // _(_tplToRequest).map(function(tplUrl){
        //     tplUrl = 'default/js/plugin/text!' + tplUrl;
        // });

        // require(_tplToRequest, function () {
        //     debugger;

        //     var _tpl = [].splice.call(arguments);
        //     var _deps = [].splice.call(arguments, 1);

        //     // register deps or check wheter we have them registered
        //     // like: if tplEngine.hasPartial

        // });


        // mpwsPage.getTemplate(_renderConfig.template, _renderConfig.dependencies, function (error, template) {

        //     var html = false;
        //     // [4] combine everything together
        //     if (template) {
        //         var templateFn = tplEngine.compile(template);
        //         // compose template data
        //         var _tplData = {
        //             app: {
        //                 config: _config,
        //                 location: {
        //                     fragment: Backbone.history.fragment
        //                 }
        //             },
        //             source: data || {}
        //         }
        //         // app.log(true, 'template data is', _tplData);
        //         html = templateFn(_tplData);
        //     }
        //     // stop ajax animation
        //     mpwsPage.setPlaceholderState(placeholder, mpwsPage.STATE.LOADING, false);
        //     // render into placeholder
        //     if (placeholder && placeholder.container) {
        //         var _injectionType = placeholder.placement || mpwsPage.PLACEMENT.REPLACE;
        //         // remove previous dom element
        //         var _elementID = $(html).filter('*').first().attr('id');
        //         // app.log(true, 'component id: ', _elementID);
        //         // app.log(true, '_injectionType: ', _injectionType);
        //         if (!_.isEmpty(_elementID))
        //             $(placeholder.container).find(_elementID.asCssID()).remove();
        //         // add new element
        //         if (_injectionType == mpwsPage.PLACEMENT.REPLACE)
        //             $(placeholder.container).html(html);
        //         else if (_injectionType == mpwsPage.PLACEMENT.PREPEND)
        //             $(placeholder.container).prepend(html);
        //         else if (_injectionType == mpwsPage.PLACEMENT.APPEND)
        //             $(placeholder.container).append(html);
        //     }
        //     _self.trigger('mview:rendered', [_self.name]);
        // });
    }

    return MView;
});