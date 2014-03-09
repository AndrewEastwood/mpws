define("plugin/shop/nls/ua_uk/toolbox", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/shop_toolbox'
], function (_, CustomerShop, PLuginAccount) {
    return _.extend({}, {
        order_status_NEW: 'Прийняте',
        order_status_ACTIVE: 'В процеси виконання',
        order_status_LOGISTIC_DELIVERING: 'Відправлено',
        order_status_LOGISTIC_DELIVERED: 'Вантаж прибув',
        order_status_SHOP_CLOSED: 'Виконано',
        shopping_cart_link_removeAll: "Видалити всі товари з списку",
        shopping_cart_form_title: "Оформлення замовлення",
        shopping_cart_field_firstName: "Імя",
        shopping_cart_field_lastName: "Прізвище",
        shopping_cart_field_email: "Ел.пошта",
        shopping_cart_field_phone: "Контактний телефон",
        shopping_cart_field_profile_address_option_none: "Не вибрано",
        shopping_cart_field_profile_address: "Мої адреси",
        shopping_cart_field_address: "Адреса доставки",
        shopping_cart_field_pobox: "Поштовий індекс",
        shopping_cart_field_country: "Країна",
        shopping_cart_field_city: "Місто",
        shopping_cart_field_logistic: "Перевізник",
        shopping_cart_field_warehouse: "Номер складу",
        shopping_cart_field_comment: "Ваші побажання",
        pluginMenuTitle: 'Інтернет Магазин',
        pluginMenu_Dashboard: 'Статистика',
        pluginMenu_Products: 'Товари',
        pluginMenu_Orders: 'Замовлення',
        pluginMenu_Offers: 'Акції та Розпродаж',
        pluginMenu_Orders_Grid_Column_ID: "#",
        pluginMenu_Orders_Grid_Column_Shipping: "Доставка",
        pluginMenu_Orders_Grid_Column_Status: "Статус",
        pluginMenu_Orders_Grid_Column_Warehouse: "Склад",
        pluginMenu_Orders_Grid_Column_DateCreated: "Створений",
        pluginMenu_Orders_Grid_Column_DateUpdated: "Оновлений",
        logisticAgency_company_gunsel: "Гюнсел",
        logisticAgency_company_novaposhta: "Нова Пошта",
        logisticAgency_company_intime: "Ін-Тайм",
        logisticAgency_company_ukrposhta: "УкрПошта",
        logisticAgency_Unknown: "Самовивіз"
    }, CustomerShop);
});