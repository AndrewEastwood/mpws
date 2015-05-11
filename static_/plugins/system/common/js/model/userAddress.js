define([
    'backbone',
], function (Backbone) {
    var UserAddress = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('system', 'address')
    });
    return UserAddress;

});