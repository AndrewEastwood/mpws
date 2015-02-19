define('plugin/shop/common/js/model/setting', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        idAttribute: 'ID',
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'settings',
                type: this.sType || null
            });
        },
        initialize: function () {
            // debugger
            if (this.collection) {
                this.setType(this.collection.getType());
            }
        },
        getType: function () {
            return this.sType;
        },
        setType: function (type) {
            this.sType = type;
            return this;
        },
        isAddress: function () {
            return this.sType === 'ADDRESS';
        },
        isCurrency: function () {
            return this.sType === 'EXCHANGERATES';
        }
    });

});