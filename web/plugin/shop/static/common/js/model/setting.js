define('plugin/shop/common/js/model/setting', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        idAttribute: "ID",
        initialize: function (opts) {
            this.url = APP.getApiLink({
                source: 'shop',
                fn: 'settings',
                type: opts && opts.type || null
            });
            this.unset('type', {silent: true});
        },
        isAddress: function () {
            return this.get('Type') === 'ADDRESS';
        },
        isCurrency: function () {
            return this.get('Type') === 'EXCHANGERATES';
        },
        getAddressUID: function () {
            if (this.isAddress()) {
                var addressMatch = this.get('Property').match(/\w+_([0-9]+)_\w+/),
                    addressUID = addressMatch && addressMatch[1];
                return addressUID;
            }
            return false;
        },
        getAddressFieldName: function () {
            if (this.isAddress()) {
                var addressMatch = this.get('Property').match(/\w+_[0-9]+_(\w+)/),
                    addressFieldsName = addressMatch && addressMatch[1];
                return addressFieldsName;
            }
            return false;
        }
    });

});