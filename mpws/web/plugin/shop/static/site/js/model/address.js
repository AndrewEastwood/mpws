define('plugin/shop/site/js/model/address', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        defaults: {
            OrganizationName: 'Twitter, Inc.',
            AddressLine1: '795 Folsom Ave, Suite 600',
            AddressLine2: 'San Francisco, CA 94107',
            Phone1: '(123) 456-7890',
            Phone2: '(123) 456-7890',
            OpenHoursWeekdays: 'Робочі дні: 10:00 - 19:00',
            OpenHoursSaturday: 'Субота: 9:00 - 18:00',
            OpenHoursSunday: 'Неділя: 9:00 - 16:00',
        }
        // idAttribute: "ID",
        // url: function () {
        //     var _params =  {
        //         source: 'shop',
        //         fn: 'product'
        //     };
        //     if (!this.isNew())
        //         _params.id = this.id;
        //     return APP.getApiLink(_params);
        // },
        // parse: function (data) {
        //     // debugger;
        //     return ShopUtils.adjustProductItem(data);
        // },
    });

});