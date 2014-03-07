define("plugin/shop/nls/ua_uk/shop", [
    // here we will call default lang pkgs to override them
    'default/js/lib/underscore',
    'customer/nls/ua_uk/shop'
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
        shopping_cart_error_EmptyShippingAddress: "Потрібно вказати адресу доставки",
        shopping_cart_error_WrongProfileAddressID: "Вибрана неіснуюча адреса з Вашого акаунту",
        shopping_cart_error_UnknownError: "Помилка збереження замовлення",
    }, CustomerShop);
});