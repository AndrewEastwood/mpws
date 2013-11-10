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



    // public class
    function pluginShopRender (options) {
        var self = this;
        var _options = options || {};
        this.model = new pluginShopModel();
        this.getOptions = function () { return _options; }
        this.getPlacehoders = function () { return _options.placeholders || {}; }
        this.componentsCommon = {};

        // setup common render components

        mpwsPageLib.createRenderConfig('categoryStructure', {
            data: {
                source: self.model.getShopCatalogStructure
            },
            template: "plugin.shop.component.catalogStructure@hbs",
            dependencies: _templatePartialsBase,
            placeholder: mpwsPageLib.createRenderPlacement(_options.placeholders.menu)
        }, this.componentsCommon);

        mpwsPageLib.createRenderConfig('cartEmbedded', {
            data: {
                source: self.model.getShoppingCart
            },
            template: "plugin.shop.component.shoppingCartEmbedded@hbs",
            dependencies: _templatePartialsBase,
            placeholder: mpwsPageLib.createRenderPlacement(_options.placeholders.shoppingCartEmbedded)
        }, this.componentsCommon);

        this.initialize();
    }

    pluginShopRender.prototype.pageShopHome = function () {
        var self = this;
        // overwrite page name
        mpwsPageLib.pageName('shop-home');

        // here we have to render all essential elements rele=atetd to this scope
        // 
        var _pholders = this.getPlacehoders();
        var _renderConfiguration = {
            productsLatest: {
                data: {
                    source: self.model.getProductListLatest
                },
                template: "plugin.shop.component.productList@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _pholders.productsLatest
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(this.componentsCommon, _renderConfiguration);
    }
    
    pluginShopRender.prototype.pageShopCatalog = function () {
        mpwsPageLib.pageName('shop-catalog');
        // _pageShopCatalog.call(this);
    }

    pluginShopRender.prototype.pageShopProductsByCategory = function (categoryId) {
        var self = this;
        // overwrite page name
        mpwsPageLib.pageName('shop-catalog-cetegory');

        // here we have to render all essential elements rele=atetd to this scope
        // 
        var _pholders = this.getPlacehoders();
        var _renderConfiguration = {
            productsByCategory: {
                data: {
                    source: self.model.getProductsByCategory,
                    params: {
                        categoryId: categoryId
                    }
                },
                template: "plugin.shop.component.catalogByCategoryList@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _pholders.productsByCategory
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(this.componentsCommon, _renderConfiguration);
    }
    
    pluginShopRender.prototype.pageShopCart = function () {
        var self = this;
        mpwsPageLib.pageName('shop-cart-view');

        var _pholders = this.getPlacehoders();
        var _renderConfiguration = {
            shoppingCartStandalone: {
                data: {
                    source: self.model.getShoppingCart
                },
                template: "plugin.shop.component.shoppingCartStandalone@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _pholders.shoppingCartStandalone
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(this.componentsCommon, _renderConfiguration);
        // mpwsPageLib.getPageBody('I AM CART', true);
    }

    pluginShopRender.prototype.pageShopCartCheckout = function () {
        var self = this;
        mpwsPageLib.pageName('shop-cart-checkout');

        var _pholders = this.getPlacehoders();
        var _renderConfiguration = {
            shoppingCartCheckout: {
                data: {
                    source: self.model.getShoppingCart
                },
                template: "plugin.shop.component.shoppingCartCheckout@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _pholders.shoppingCartCheckout,
                callback: function () {
                    self.action_cartEmbeddedHide();
                }
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(this.componentsCommon, _renderConfiguration);
    }

    
    pluginShopRender.prototype.pageProductListLatest = function () {
        mpwsPageLib.pageName('shop-list-latest');
        // _pageShopProductListLatest.call(this);
    }
    
    pluginShopRender.prototype.pageShopProductItemByID = function (productId) {
        var self = this;
        mpwsPageLib.pageName('shop-product');
        var _pholders = this.getPlacehoders();
        var _renderConfiguration = {
            productData: {
                data: {
                    source: self.model.getProductItemByID
                },
                template: "plugin.shop.component.productItem@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _pholders.productItem
            }
        };
        app.log(true, _renderConfiguration);
        mpwsPageLib.render(this.componentsCommon, _renderConfiguration);
    }   
    
    pluginShopRender.prototype._test_getProductAttributes = function (productId) {
        this.model.getProductAttributes.call(this, productId);
    }
    
    pluginShopRender.prototype._test_getProductPriceArchive = function (productId) {
        this.model.getProductPriceArchive.call(this, productId);
    }
    
    pluginShopRender.prototype.action_cartEmbeddedShow = function() {
        var _pholder = this.getPlacehoders().shoppingCartEmbedded;
        $(_pholder.target).toggleClass('open', true);
    };

    pluginShopRender.prototype.action_cartEmbeddedHide = function() {
        var _pholder = this.getPlacehoders().shoppingCartEmbedded;
        $(_pholder.target).toggleClass('open', false);
    };

    pluginShopRender.prototype.initialize = function (startHistory) {
        var self = this;

        // init actions
        $('body').on('click', '[data-action]', function () {
            var _action = $(this).data('action');
            var _oid = $(this).data('oid');

            switch (_action) {
                case "shop:buy":
                    self.model.shoppingCartAdd(_oid, function (rez) {
                        var _cartEmbeddedRenderConfig = mpwsPageLib.modifyRenderConfig(self.componentsCommon.cartEmbedded, {
                            callback: function () {
                                self.action_cartEmbeddedShow();
                            }
                        });
                        mpwsPageLib.render(_cartEmbeddedRenderConfig);
                        // _libHtml.messageBox('You"re going buy: ' + _oid);
                    });
                    break;
                case "shop:cart:embedded-item-remove":
                    self.model.shoppingCartRemove(_oid, function (rez) {
                        // self.pageShopCart();
                        var _cartEmbeddedRenderConfig = mpwsPageLib.modifyRenderConfig(self.componentsCommon.cartEmbedded, {
                            callback: function () {
                                self.action_cartEmbeddedShow();
                            }
                        });
                        mpwsPageLib.render(_cartEmbeddedRenderConfig);
                    });
                    break;
                case "shop:cart:embedded-clear":
                    self.model.shoppingCartClear(function (rez) {
                        // self.pageShopCart();
                        var _cartEmbeddedRenderConfig = mpwsPageLib.modifyRenderConfig(self.componentsCommon.cartEmbedded, {
                            callback: function () {
                                // self.action_cartEmbeddedHide();
                                if (self.router.isRouteActive(self.router.navMap.shop_cart_view))
                                    self.router.refreshPage();
                                if (self.router.isRouteActive(self.router.navMap.shop_cart_checkout))
                                    self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                            }
                        });
                        mpwsPageLib.render(_cartEmbeddedRenderConfig);
                    });
                    break;
                case "shop:cart:standalone-item-remove":
                    self.model.shoppingCartRemove(_oid, function (rez) {
                        // self.pageShopCart();
                        self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                    });
                    break;
                case "shop:cart:standalone-clear":
                    self.model.shoppingCartClear(function (rez) {
                        self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                        // self.router.refreshPage();
                    });
                    break;
                case "shop:cart:view": {
                    self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                    break;
                }
                case "shop:cart:checkout":
                    self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_checkout);
                    break;
                default:
                    self.action_cartEmbeddedHide();
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