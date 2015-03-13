define([
    'backbone'
], function (Backbone) {

    var Customer = Backbone.Model.extend({
        idAttribute: "ID",
        urlRoot: APP.getApiLink('system', 'customers')
    });

    return Customer;
});