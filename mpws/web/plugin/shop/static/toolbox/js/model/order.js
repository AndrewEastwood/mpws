define('plugin/shop/toolbox/js/model/order', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone'
], function (Sandbox, Backbone) {

    var Order = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'order'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        saveOrderStatus: function (status) {
            var self = this;
            var previous = this.toJSON();
            return this.save({
                Status: status
            }, {
                patch: true,
                silent: true,
                success: function (model/*, resp, options*/) {
                    // debugger;
                    // var diff = model.changedAttributes(previous);
                    // model.changed = diff;
                    // var args = [].slice.call(arguments);
                    // args.unshift('change');
                    // self.trigger.apply(this, args);
                    Sandbox.eventNotify('plugin:shop:order:changed', {
                        current: model.toJSON(),
                        previous: previous
                    });
                    self.trigger('change');
                }
            });
        }
    });

    return Order;

});