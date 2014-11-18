define('plugin/shop/site/js/model/address', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        defaults: {
            OrganizationName: 'Leogroup',
            // AddressLine1: '795 Folsom Ave, Suite 600',
            // AddressLine2: 'San Francisco, CA 94107',
            Phone1: '(050) 1000-430',
            Phone2: '(096) 677-70-97',
            OpenHoursWeekdays: 'Робочі дні: 10:00 - 19:00',
            OpenHoursSaturday: 'Субота: 10:00 - 18:00',
            OpenHoursSunday: 'Неділя: вихідний',
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