APP.Modules.register("plugin/shop/view/render", [], [
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/mpws.page',
    'plugin/shop/lib/driver'
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
        productEntryViewList: {
            url: "plugin.shop.component.productEntryViewList@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
        shopSideBar: {
            url: "plugin.shop.component.pageSidebar@hbs",
            type: mpwsPage.TYPE.PARTIAL
        },
        widgetCities: {
            url: "plugin.shop.component.widgetCities@hbs",
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

        // catefory structure (injected into main menu toolbar)
        mpwsPageLib.createRenderConfig('categoryStructure', {
            isRequiredOnce: true,
            data: {
                source: self.model.getShopCatalogStructure
            },
            template: "plugin.shop.component.catalogStructure@hbs",
            placeholder: mpwsPageLib.createRenderPlacement(_options.placeholders.menu)
        }, this.componentsCommon);

        // embedded car (simple cart content)
        mpwsPageLib.createRenderConfig('cartEmbedded', {
            isRequiredOnce: false,
            data: {
                source: self.model.getShoppingCart
            },
            template: "plugin.shop.component.shoppingCartEmbedded@hbs",
            placeholder: mpwsPageLib.createRenderPlacement(_options.placeholders.shoppingCartEmbedded)
        }, this.componentsCommon);

        // breadcrumb
        mpwsPageLib.createRenderConfig('shopBreadcrumb', {
            isRequiredOnce: false,
            data: {
                source: self.model.getShopLocation,
                params: {
                    categoryId: 16
                }
            },
            template: "plugin.shop.component.breadcrumb@hbs",
            placeholder: mpwsPageLib.createRenderPlacement(_options.placeholders.breadcrumb)
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

    pluginShopRender.prototype.pageShopCartCheckoutPreview = function () {
        var self = this;
        mpwsPageLib.pageName('shop-cart-checkout-preview');

        var _pholders = this.getPlacehoders();
        var _renderConfiguration = {
            shoppingCartCheckout: {
                data: {
                    source: self.model.getShoppingCart
                },
                template: "plugin.shop.component.shoppingCartCheckoutPreview@hbs",
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

    pluginShopRender.prototype.pageShopCartCheckoutSave = function () {
        var self = this;
        mpwsPageLib.pageName('shop-cart-checkout-save');
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
                    source: self.model.getProductItemByID,
                    params: {
                        productId: productId
                    }
                },
                template: "plugin.shop.component.productEntryViewStandalone@hbs",
                dependencies: _templatePartialsBase,
                placeholder: _pholders.productEntryViewStandalone
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

    pluginShopRender.prototype.initialize = function () {
        var self = this;

        $('.component-shop-recently-viewed-embedded').on('click', function (){
            _libHtml.messageBox('test');
        })

        // init actions
        $('body').on('click', '[data-action]', function () {
            var _action = $(this).data('action');
            var _oid = $(this).data('oid');

            switch (_action) {
                // wishlist
                case "shop:wishlist:add":
                    break;
                case "shop:wishlist:remove":
                    break;
                // compare
                case "shop:compare:add":
                    break;
                case "shop:compare:remove":
                    break;
                // cart
                case "shop:cart:add":
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
                                if (self.router.isRouteActive(self.router.navMap.shop_cart_checkout_view))
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
                case "shop:cart:view":
                    self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_view);
                    break;
                case "shop:cart:checkout":
                    self.router.redirectOrRefreshPage(self.router.navMap.shop_cart_checkout_view);
                    break;
                case "shop:cart:checkout:preview":
                    self.pageShopCartCheckoutPreview();
                    break;
                case "shop:cart:checkout:save":
                    var _orderInfo = {};
                    // use form serialize
                    self.model.shoppingCartSave(_orderInfo, function(){

                    });
                default:
                    self.action_cartEmbeddedHide();
                    break;
            }
        });

        // subscribe on global events
        Sandbox.eventSubscribe('mpws:page:render-complete-all', function (data) {
            app.log(true, 'on mpws:page:render-complete-all', data);

            // init price filtering
             $(function() {
                $( "#slider-range" ).slider({
                  range: true,
                  min: 0,
                  max: 500,
                  values: [ 75, 300 ],
                  slide: function( event, ui ) {
                    $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                  }
                });
                $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
                  " - $" + $( "#slider-range" ).slider( "values", 1 ) );
              });

             // simple main image zoom
             $('.shop-product-image-main .image').magnify();

        });

    }

    // $('.icon-home').on('click', function(){
    //     model.getShopCatalogStructure(function(data){
    //         app.log('TEST getShopCatalogStructure', data);
    //     });
    // })

    return pluginShopRender;

});