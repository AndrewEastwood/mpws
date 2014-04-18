define("plugin/shop/nls/ua_uk/toolbox", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/shop_toolbox'
], function (_, CustomerShop) {
    return _.extend({}, {
        // orderEntry_Popup_field_firstName: "Імя",
        // orderEntry_Popup_field_lastName: "Прізвище",
        // orderEntry_Popup_field_email: "Ел.пошта",
        // orderEntry_Popup_field_phone: "Контактний телефон",
        // orderEntry_Popup_field_profile_address_option_none: "Не вибрано",
        // orderEntry_Popup_field_profile_address: "Мої адреси",
        // orderEntry_Popup_field_address: "Адреса доставки",
        // orderEntry_Popup_field_pobox: "Поштовий індекс",
        // orderEntry_Popup_field_country: "Країна",
        // orderEntry_Popup_field_city: "Місто",
        // orderEntry_Popup_field_logistic: "Перевізник",
        // orderEntry_Popup_field_warehouse: "Номер складу",
        // orderEntry_Popup_field_comment: "Коментар",
        // menu
        pluginMenuTitle: 'Інтернет Магазин',
        pluginMenu_Dashboard: 'Статистика',
        pluginMenu_Products: 'Товари',
        pluginMenu_Orders: 'Замовлення',
        pluginMenu_Offers: 'Акції та Розпродаж',
        pluginMenu_Reports: 'Звіти',
        // Order list
        pluginMenu_Orders_Grid_Column_ID: "#",
        pluginMenu_Orders_Grid_Column_Shipping: "Доставка",
        pluginMenu_Orders_Grid_Column_Status: "Статус",
        pluginMenu_Orders_Grid_Column_Warehouse: "Склад",
        pluginMenu_Orders_Grid_Column_DateCreated: "Створений",
        pluginMenu_Orders_Grid_Column_DateUpdated: "Оновлений",
        pluginMenu_Orders_Grid_Column_Actions: "Операції",
        pluginMenu_Orders_Grid_link_Edit: "Редагувати",
        pluginMenu_Orders_Grid_link_ShowDeleted: "Показати виконані",
        // Order entry popup
        orderEntry_Popup_title: "Замовлення #",
        orderEntry_Popup_section_boughts: "Список придбаних товарів:",
        orderEntry_Popup_control_status: "Виберіть статус замовлення",
        orderEntry_Popup_button_OK: "Добре",
        // Product list
        pluginMenu_Products_Grid_Column_Name: "Назва",
        pluginMenu_Products_Grid_Column_Model: "Модель",
        pluginMenu_Products_Grid_Column_SKU: "SKU",
        pluginMenu_Products_Grid_Column_Price: "Ціна",
        pluginMenu_Products_Grid_Column_Status: "Статус",
        pluginMenu_Products_Grid_Column_DateUpdated: "Оновлений",
        pluginMenu_Products_Grid_Column_DateCreated: "Створений",
        pluginMenu_Products_Grid_Column_Actions: "Операції",
        product_type_Active: "Активні",
        product_type_Inactive: "Неактивні",
        product_type_Uncompleted: "Незаповнені",
        product_type_Sales: "Акції та розпродажі",
        product_type_Popular: "Популярні",
        product_type_NotPopular: "Непопулярні",
    }, CustomerShop);
});