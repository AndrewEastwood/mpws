define("plugin/account/common/js/model/accountAddress", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
], function (Sandbox, Backbone) {

    var AccountAddress = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'account',
                fn: 'address'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        initialize: function () {
            Sandbox.eventSubscribe('global:route', $.proxy(function () {
                this.unset('errors', {silent: true});
                this.unset('success', {silent: true});
            }, this));
        }
    });
    return AccountAddress;

});