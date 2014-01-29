define("default/js/model/breadcrumb", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url'
], function (_, Backbone, Url) {

    var Breadcrumb = Backbone.Model.extend({

        _options: {

            fn: null

            // categoryId: null,

            // productId: null,
        },

        configure: function (options) {
            this._options = _.extend({}, this._options, options);
        }

    });

    return Breadcrumb;

});