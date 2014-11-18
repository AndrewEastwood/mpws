define('plugin/shop/common/js/model/setting', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'settings'
            };
            if (!this.isNew())
                _params.id = this.id;
            if (this.isNew() && this.get('name'))
                _params.name = this.get('name');
            return APP.getApiLink(_params);
        },
        isAddress: function () {
            return this.get('Type') === 'ADDRESS';
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