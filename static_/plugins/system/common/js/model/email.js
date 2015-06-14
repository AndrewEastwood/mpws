define([
    'backbone'
], function (Backbone) {

    var Email = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('system', 'email')
    });

    return Email;
});