APP.Modules.register("plugin/shop/view/render", [], [
    'lib/jquery',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/driver',
], function (app, Sandbox, $, mpwsAPI, mpwsPage, pluginShopDriver) {

    var pluginShopLib = new pluginShopDriver();
    var mpwsPageLib = new mpwsPage();

    var _logPrefix = '[plugin/shop/view/render] : ';

    // app.log('HI FROM SHOP RENDER :) I AM SHOP RENDER LIBRARY YO')

    function pluginShopRender () {}

    pluginShopRender.prototype.getProductListLatest = function () {
        mpwsPageLib.setPageContentByTemplate("plugin.shop.component.productList@hbs", function (onDataReceived) {
            pluginShopLib.getProductListLatest(onDataReceived);
        });
    }

    pluginShopRender.prototype.getProductItemByID = function (productId) {
        mpwsPageLib.setPageContentByTemplate("plugin.shop.component.productItem@hbs", function (onDataReceived) {
            pluginShopLib.getProductItemByID(productId, onDataReceived);
        });
    }

    return pluginShopRender;

});