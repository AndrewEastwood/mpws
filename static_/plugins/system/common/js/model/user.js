define([
    'jquery',
    'underscore',
    'backbone'
], function ($, _, Backbone) {

    var instance = null;

    var User = Backbone.Model.extend({
        idAttribute: 'ID',
        urlRoot: APP.getApiLink('system', 'users'),
        // url: function () {
        //     var _params =  {
        //         source: 'system',
        //         fn: 'users'
        //     };
        //     if (!this.isNew())
        //         _params.id = this.id;
        //     return APP.getApiLink(_params);
        // },
        // initialize: function () {
            // autoclean errors when user goes wherever else
            // APP.Sandbox.eventSubscribe('global:route', $.proxy(function () {
            //     this.unset('errors', {silent: true});
            //     this.unset('success', {silent: true});
            // }, this));
        // },
        changePassword: function (password, confirmation) {
            var data = {
                Password: password,
                ConfirmPassword: confirmation
            };
            return this.save(data/*, {patch: true}*/);
        }//,
        // isSaved: function () {
        //     return this.get('success');
        // }

    }, {
        getInstance: function (options) {
            if (instance) {
                return instance;
            } else {
                instance = new User(options);
                return instance;
            }
        }
    });

    return User;

});