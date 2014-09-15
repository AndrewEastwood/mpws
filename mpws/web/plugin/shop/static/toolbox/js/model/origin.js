define('plugin/shop/toolbox/js/model/origin', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Origin = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'origin'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        initialize: function () {
            this.on('change:Name change:Description change:HomePage', function () {
                this.save(this.changed, {
                    patch: true
                });
            }, this);
        }
    });

    return Origin;
});