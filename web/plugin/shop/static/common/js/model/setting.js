define('plugin/shop/common/js/model/setting', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'setting'
            };
            if (!this.isNew())
                _params.id = this.id;
            if (this.isNew() && this.get('name'))
                _params.name = this.get('name');
            return APP.getApiLink(_params);
        }
    });

});