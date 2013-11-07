APP.Modules.register("plugin/shop/view/render", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/driver',
    'lib/async'
], function (app, Sandbox, $, _, Backbone, mpwsAPI, mpwsPage, pluginShopDriver, AsyncLib) {

    var pluginShopDataLib = new pluginShopDriver();
    var mpwsPageLib = new mpwsPage();

    var _logPrefix = '[plugin/shop/view/render] : ';

    // mpwsPageLib.setupDependencies({
    //     productListItem: "plugin.shop.component.productListItem@hbs"
    // });

    // app.log('HI FROM SHOP RENDER :) I AM SHOP RENDER LIBRARY YO')

    var _templatePartialsBase = {
        renderDataTrigger: {
            url: "plugin.shop.component.renderDataTrigger@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
        productList: {
            url: "plugin.shop.component.productList@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
        productListItem: {
            url: "plugin.shop.component.productListItem@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
        shopSideBar: {
            url: "plugin.shop.component.pageSidebar@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
        productItem: {
            url: "plugin.shop.component.productItem@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
    }

    var _pageElemenatsBase = {
        categoryStructure: function (callback) {
            pluginShopDataLib.getShopCatalogStructure(callback);
        },
    }

    // shop start page
    function _pageShopHome () {
        // _pageShopProductListLatest();
        // overwrite page name
        mpwsPageLib.pageName('shop-home');

        // here we have to render all essential elements rele=atetd to this scope
        // 
        var _opts = this.getPlacehoders();
        var _renderConfiguration = {
            categoryStructure: {
                data: {
                    source: pluginShopDataLib.getShopCatalogStructure
                },
                template: "plugin.shop.component.catalogStructure@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.menu
            },
            productsLatest: {
                data: {
                    source: pluginShopDataLib.getProductListLatest
                },
                template: "plugin.shop.component.productList@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.productsLatest
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(_renderConfiguration);
            //  function (callback) {


            //     pluginShopDataLib.getShopCatalogStructure(onDataReceived);


            //     mpwsPageLib.render("plugin.shop.page.publicHome@hbs", _templatePartialsBase, function (onDataReceived) {
            //         pluginShopDataLib.getShopCatalogStructure(onDataReceived);
            //     }, false, function (error, html) {
            //         callback(error, html);
            //     });
            // },
            // productsLatest: function (callback) {
            //     pluginShopDataLib.getProductListLatest(callback);
            // },

    //     AsyncLib.parallel(_renderComponents);


    //     mpwsPageLib.render("plugin.shop.page.publicHome@hbs", _templatePartialsBase, function (onDataReceived) {
    //         var _pageElements = _.extend(_pageElemenatsBase, {
    //             productListLatest: function (callback) {
    //                 pluginShopDataLib.getProductListLatest(callback);
    //             }
    //         });
    //         AsyncLib.parallel(_pageElements, function(e,d){
    //             // app.log(true, 'dddddddddddddddddddddddd=>>>>', d);
    //             onDataReceived(e,d);
    //         });
    //         // pluginShopDataLib.getProductListLatest(function (error, data) {
    //         //     onDataReceived(error, data);
    //         // });
    //     });
    }

    // shop products lists
    function _pageShopProductListLatest () {
        mpwsPageLib.pageName('shop-list-latest');
        // mpwsPageLib.render("plugin.shop.page.publicProductListLatest@hbs", _templatePartialsBase, function (onDataReceived) {
        //     pluginShopDataLib.getProductListLatest(onDataReceived);
        // });
    }

    // shop catalog options
    function _pageShopCatalog () {
        mpwsPageLib.pageName('shop-catalog');
        // mpwsPageLib.render("plugin.shop.page.publicCatalog@hbs", _templatePartialsBase, function (onDataReceived) {
        //     pluginShopDataLib.getProductListLatest(onDataReceived);
        // });
    }

    function _pageShopCatalogByCategory (categoryId) {
        mpwsPageLib.pageName('shop-category');
        // mpwsPageLib.render("plugin.shop.page.publicCatalogCategory@hbs", _templatePartialsBase, function (onDataReceived) {
        //     pluginShopDataLib.getProductListLatest(onDataReceived);
        // });

    }

    function _pageShopCatalogByCategoryAndBrand (categoryId, brandId) {
        mpwsPageLib.pageName('shop-category-brand');
        // mpwsPageLib.render("plugin.shop.page.publicCatalogCategoryBrand@hbs", _templatePartialsBase, function (onDataReceived) {
        //     pluginShopDataLib.getProductListLatest(categoryId, onDataReceived);
        // });
    }

    // shop product item
    function _pageShopProductItemByID (productId) {
        mpwsPageLib.pageName('shop-product');
        // mpwsPageLib.render("plugin.shop.page.publicProductItem@hbs", _templatePartialsBase, function (onDataReceived) {
        //     pluginShopDataLib.getProductItemByID(productId, onDataReceived);
        // });
    }

    // shop cart
    function _pageShopCart () {
        mpwsPageLib.pageName('shop-cart');
        mpwsPageLib.getPageBody('fdfsdfdsf', true);
    }


    // public class
    function pluginShopRender (options) {
        var _options = options || {};
        this.getOptions = function () { return _options; }
        this.getPlacehoders = function () { return _options.placeholders || {}; }
    }

    pluginShopRender.prototype.pageShopHome = function () {
        _pageShopHome.call(this);
    }
    pluginShopRender.prototype.pageShopCatalog = function () {
        _pageShopCatalog.call(this);
    }
    pluginShopRender.prototype.pageProductListLatest = function () {
        _pageShopProductListLatest.call(this);
    }
    pluginShopRender.prototype.pageShopProductItemByID = function (productId) {
        _pageShopProductItemByID.call(this, productId);
    }   
    pluginShopRender.prototype._test_getProductAttributes = function (productId) {
        pluginShopDataLib.getProductAttributes.call(this, productId);
    }
    pluginShopRender.prototype._test_getProductPriceArchive = function (productId) {
        pluginShopDataLib.getProductPriceArchive.call(this, productId);
    }
    pluginShopRender.prototype.start = function (startHistory) {
        var controller = new Controller(); // Создаём контроллер
        if (startHistory)
            Backbone.history.start();  // Запускаем HTML5 History push    
    }

    $('.icon-home').on('click', function(){
        pluginShopDataLib.getShopCatalogStructure(function(data){
            app.log('TEST getShopCatalogStructure', data);
        });
    })

    return pluginShopRender;

});