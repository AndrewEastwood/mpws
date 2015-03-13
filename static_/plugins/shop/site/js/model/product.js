define([
    'backbone',
    'underscore'
], function (Backbone, _) {
    // debugger;
    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'products',
                id: this.id
            };
            return APP.getApiLink(_params);
        }
    });
});