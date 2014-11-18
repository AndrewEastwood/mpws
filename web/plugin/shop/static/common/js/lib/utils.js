define("plugin/shop/common/js/lib/utils", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
], function (Sandbox, $, _) {

    function Utils () {};

    // Utils.adjustProductItems = function (products) {
    //     if (!products)
    //         return false;

    //     _(products).each(function(product) {
    //         Utils.adjustProductItem(product);
    //         if (product.Relations)
    //             _(products.Relations).each(function(relatedProduct) {
    //                 Utils.adjustProductItem(relatedProduct);
    //             });
    //     });

    //     return products;
    // }

    // Utils.adjustProductItem = function (product) {

    //     product.Attributes = product.Attributes || {};
    //     product.Features = product.Features || {};

    //     // get product attributes
    //     var _attr = product.Attributes;

    //     // setup images
    //     var _images = {
    //         HAS_MAIN: false,
    //         HAS_ADDITIONAL: false,
    //         MAIN: false,
    //         ADDITIONAL : false
    //     }

    //     // adjust product images
    //     if (_attr.IMAGE) {
    //         if (_.isString(_attr.IMAGE)) {
    //             _images.HAS_MAIN = true;
    //             _images.MAIN = _attr.IMAGE;
    //         }
    //         if (_.isArray(_attr.IMAGE)) {
    //             _images.HAS_MAIN = true;
    //             _images.MAIN = _attr.IMAGE.shift();
    //             if (_attr.IMAGE.length) {
    //                 _images.HAS_ADDITIONAL = true;
    //                 _images.ADDITIONAL = _attr.IMAGE;
    //             }
    //         }
    //     } else {
    //         _images.MAIN = _images.EMPTY;
    //     }

    //     _attr.IMAGES = _images;

    //     return product;
    // }

    // Utils.updateOrderStatus = function (orderID, status) {
    //     // debugger;
    //     return $.post(APP.getApiLink({
    //         source: 'shop',
    //         fn: 'shop_manage_orders',
    //         orderID: orderID,
    //         action: "update",
    //     }), {
    //         Status: status
    //     }, function(data){
    //         Sandbox.eventNotify('plugin:shop:order:item:updated', data);
    //     });
    // }

    // Utils.getOriginStatusList = function () {
    //     return $.post(APP.getApiLink({
    //         source: 'shop',
    //         fn: 'shop_manage_origins',
    //         action: 'statuses'
    //     }), function(data){
    //         Sandbox.eventNotify('plugin:shop:origin:received:statuslist', data);
    //     });
    // }

    // Utils.getOriginList = function () {
    //     return $.get(APP.getApiLink({
    //         source: 'shop',
    //         fn: 'shop_manage_origins',
    //         action: 'list'
    //     }), function(data){
    //         Sandbox.eventNotify('plugin:shop:origin:received:list', data);
    //     });
    // }


    // Utils.updateOriginField = function (originID, field, value) {
    //     return $.ajax({
    //         type: "PUT",
    //         url: APP.getApiLink({
    //             source: 'shop',
    //             fn: 'shop_manage_origin'
    //         }),
    //         contentType: "application/json",
    //         data: JSON.stringify({
    //             ID: originID,
    //             field: field,
    //             value: value
    //         })
    //     });
    // }

    return Utils;

});