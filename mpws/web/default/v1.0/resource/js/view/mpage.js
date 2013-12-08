APP.Modules.register("view/mpage", [], [
    'lib/jquery',
    'lib/backbone',
    'lib/mpws.page',
], function (app, Sandbox, $, Backbone, mpwsPage) {

    var MPage = Backbone.View.extend({

        name: "default",

        viewItems: {},

        initialize: function(options) {
            app.log(true ,'view MPage initialize');
            // overwrite page name
            this.setPageName(this.name);
        },

        setPageName: function (name) {
            this.options.name = name;
            mpwsPage.pageName(this.name);
        }

    });

    return MPage;
});