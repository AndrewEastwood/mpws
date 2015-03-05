define([
    'backbone',
], function (Backbone) {

    var UserAddress = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'system',
                fn: 'address'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        // initialize: function () {
        //     APP.Sandbox.eventSubscribe('global:route', $.proxy(function () {
        //         this.unset('errors', {silent: true});
        //         this.unset('success', {silent: true});
        //     }, this));
        // }
    });
    return UserAddress;

});