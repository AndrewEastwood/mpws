define([
    'backbone',
    'underscore'
], function (Backbone, _) {
    // debugger;
    return Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('shop', 'products')
    });
});