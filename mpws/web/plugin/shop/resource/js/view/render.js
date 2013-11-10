APP.Modules.register("plugin/shop/view/render", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/driver',
], function (app, Sandbox, $, _, Backbone, mpwsAPI, mpwsPage, pluginShopModel) {

    var _logPrefix = '[plugin/shop/view/render] : ';
    var mpwsPageLib = new mpwsPage();

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

    // // shop start page
    // // shop products lists
    // function _pageShopProductListLatest () {
    //     // mpwsPageLib.render("plugin.shop.page.publicProductListLatest@hbs", _templatePartialsBase, function (onDataReceived) {
    //     //     model.getProductListLatest(onDataReceived);
    //     // });
    // }

    // // shop catalog options
    // function _pageShopCatalog () {
    //     // mpwsPageLib.render("plugin.shop.page.publicCatalog@hbs", _templatePartialsBase, function (onDataReceived) {
    //     //     model.getProductListLatest(onDataReceived);
    //     // });
    // }

    // function _pageShopCatalogByCategory (categoryId) {
    //     mpwsPageLib.pageName('shop-category');
    //     // mpwsPageLib.render("plugin.shop.page.publicCatalogCategory@hbs", _templatePartialsBase, function (onDataReceived) {
    //     //     model.getProductListLatest(onDataReceived);
    //     // });

    // }

    // function _pageShopCatalogByCategoryAndBrand (categoryId, brandId) {
    //     mpwsPageLib.pageName('shop-category-brand');
    //     // mpwsPageLib.render("plugin.shop.page.publicCatalogCategoryBrand@hbs", _templatePartialsBase, function (onDataReceived) {
    //     //     model.getProductListLatest(categoryId, onDataReceived);
    //     // });
    // }

    // // shop product item
    // function _pageShopProductItemByID (productId) {
    //     mpwsPageLib.pageName('shop-product');
    //     var _opts = this.getPlacehoders();
    //     var _renderConfiguration = {
    //         categoryStructure: {
    //             data: {
    //                 source: model.getShopCatalogStructure
    //             },
    //             template: "plugin.shop.component.catalogStructure@hbs",
    //             dependencies: _templatePartialsBase,
    //             placeholder: _opts.menu
    //         },
    //         productData: {
    //             data: {
    //                 source: model.getProductItemByID
    //             },
    //             template: "plugin.shop.component.productItem@hbs",
    //             dependencies: _templatePartialsBase,
    //             placeholder: _opts.productsLatest
    //         }
    //     };
    //     app.log(true, _renderConfiguration);
    //     mpwsPageLib.render(_renderConfiguration);
    //     // mpwsPageLib.render("plugin.shop.page.publicProductItem@hbs", _templatePartialsBase, function (onDataReceived) {
    //     //     model.getProductItemByID(productId, onDataReceived);
    //     // });
    // }


    // public class
    function pluginShopRender (options) {
        var _options = options || {};
        this.model = new pluginShopModel();
        this.getOptions = function () { return _options; }
        this.getPlacehoders = function () { return _options.placeholders || {}; }

        this.initialize();
    }

    pluginShopRender.prototype.pageShopHome = function () {
        var self = this;
        // overwrite page name
        mpwsPageLib.pageName('shop-home');

        // here we have to render all essential elements rele=atetd to this scope
        // 
        var _opts = this.getPlacehoders();
        var _renderConfiguration = {
            categoryStructure: {
                data: {
                    source: self.model.getShopCatalogStructure
                },
                template: "plugin.shop.component.catalogStructure@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.menu
            },
            productsLatest: {
                data: {
                    source: self.model.getProductListLatest
                },
                template: "plugin.shop.component.productList@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.productsLatest
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(_renderConfiguration);
    }
    
    pluginShopRender.prototype.pageShopCatalog = function () {
        mpwsPageLib.pageName('shop-catalog');
        // _pageShopCatalog.call(this);
    }
    
    pluginShopRender.prototype.pageShopCart = function () {
        var self = this;
        mpwsPageLib.pageName('shop-cart');

        var _opts = this.getPlacehoders();
        var _renderConfiguration = {
            categoryStructure: {
                data: {
                    source: self.model.getShopCatalogStructure
                },
                template: "plugin.shop.component.catalogStructure@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.menu
            },
            shoppingChartStandalone: {
                data: {
                    source: self.model.getShoppingChart
                },
                template: "plugin.shop.component.shoppingChartStandalone@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.shoppingChartStandalone
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(_renderConfiguration);
        // mpwsPageLib.getPageBody('I AM CHART', true);
    }
    
    pluginShopRender.prototype.pageProductListLatest = function () {
        mpwsPageLib.pageName('shop-list-latest');
        // _pageShopProductListLatest.call(this);
    }
    
    pluginShopRender.prototype.pageShopProductItemByID = function (productId) {
        var self = this;
        mpwsPageLib.pageName('shop-product');
        var _opts = this.getPlacehoders();
        var _renderConfiguration = {
            categoryStructure: {
                data: {
                    source: self.model.getShopCatalogStructure
                },
                template: "plugin.shop.component.catalogStructure@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.menu
            },
            productData: {
                data: {
                    source: self.model.getProductItemByID
                },
                template: "plugin.shop.component.productItem@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _opts.productsLatest
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(_renderConfiguration);
    }   
    
    pluginShopRender.prototype._test_getProductAttributes = function (productId) {
        this.model.getProductAttributes.call(this, productId);
    }
    
    pluginShopRender.prototype._test_getProductPriceArchive = function (productId) {
        this.model.getProductPriceArchive.call(this, productId);
    }
    
    pluginShopRender.prototype.initialize = function (startHistory) {
        var self = this;

        // init actions
        $('body').on('click', '[data-action]', function () {
            var _action = $(this).data('action');
            var _oid = $(this).data('oid');

            switch (_action) {
                case "shop:buy":
                    self.model.shoppingChartAdd(_oid, function (rez) {
                        _libHtml.messageBox('You"re going buy: ' + _oid);
                    });
                    break;
                case "shop:chart:item-remove":
                    self.model.shoppingChartRemove(_oid, function (rez) {
                        self.pageShopCart();
                    });
                    break;
                case "shop:chart:clear":
                    self.model.shoppingChartClear(function (rez) {
                        self.pageShopCart();
                    });
                    break;
            }
        })
    }

    // $('.icon-home').on('click', function(){
    //     model.getShopCatalogStructure(function(data){
    //         app.log('TEST getShopCatalogStructure', data);
    //     });
    // })

    return pluginShopRender;

});