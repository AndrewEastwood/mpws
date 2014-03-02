define("plugin/shop/nls/ua_uk/shop", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'default/js/plugin/i18n!customer/nls/shop'
], function (_, CustomerShop) {
    return _.extend({}, {
    	order_status_NEW: 'Прийняте',
    	order_status_ACTIVE: 'В процеси виконання',
    	order_status_LOGISTIC_DELIVERING: 'Відправлено',
    	order_status_LOGISTIC_DELIVERED: 'Вантаж прибув',
    	order_status_SHOP_CLOSED: 'Виконано',
    }, CustomerShop);
});