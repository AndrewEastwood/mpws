define([
    'jquery',
    'underscore',
    'backbone',
    'plugins/system/common/js/model/email',
    'utils',
    'cachejs'
], function ($, _, Backbone, ModelEmail, Utils, Cache) {

    var Emails = Backbone.Collection.extend({
        model: ModelEmail,
        url: APP.getApiLink('system', 'email', {type: 'simplelist'})
    });

    return Emails;

});