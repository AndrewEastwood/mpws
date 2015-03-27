define([
    'jquery',
    'underscore',
    'backbone',
    'cachejs',
    'auth',
    // 'plugins/shop/site/js/view/home',
    'plugins/shop/site/js/view/listProductCatalog',
    'plugins/shop/site/js/view/productItemFull',
    'plugins/shop/site/js/view/listProductCompare',
    'plugins/shop/site/js/view/cartStandalone',
    'plugins/shop/site/js/view/listProductWish',
    'plugins/shop/site/js/view/trackingStatus',
    // 'plugins/shop/site/js/view/profileOrders',

    'plugins/shop/site/js/view/menuCart',
    'plugins/shop/site/js/view/menuWishList',
    'plugins/shop/site/js/view/menuCompare',
    'plugins/shop/site/js/view/menuPayment',
    'plugins/shop/site/js/view/menuWarranty',
    'plugins/shop/site/js/view/menuShipping',
    // 'plugins/shop/site/js/view/menuCatalog',

    'plugins/shop/site/js/view/widgetAddress',
    'plugins/shop/site/js/view/widgetExchangeRates',
    'plugins/shop/site/js/view/orderTrackingButton',
    'plugins/shop/site/js/view/cartEmbedded',
    'plugins/shop/site/js/view/menuCatalogBar',

    'plugins/shop/site/js/model/order',
    'plugins/shop/common/js/model/setting'
], function ($, _, Backbone, Cache, Auth, 
    /*PageHome,*/
    ListProductCatalog, ViewProductItemFull,
    ListProductCompare, CartStandalone, ListProductWish, TrackingStatus,
/*    ProfileOrders, */
    // menu views
    ViewMenuItemCart,
    ViewMenuItemWishList,
    ViewMenuItemCompareList,
    ViewMenuItemPopupInfoPayment,
    ViewMenuItemPopupInfoWarranty,
    ViewMenuItemPopupInfoShipping,
    // widgets
    ViewWidgetAddresses,
    ViewWidgetExchangeRates,
    ViewWidgetOrderTrackingButton,
    ViewWidgetCartEmbedded,
    ViewWidgetCatalogBar,
    SiteOrder, SiteSettings) {

    var order = new SiteOrder({
        ID: "temp"
    });

    // why it's here?
    order.url = APP.getApiLink({
        source: 'shop',
        fn: 'orders'
    });

    // var $dfdSettings = settings.fetch();

    // var routes = {
    //     "!/shop": "home",
    //     "!/shop/catalog/:category": "shop_catalog_category",
    //     "!/shop/catalog/:category/:page": "shop_catalog_category_page",
    //     "!/shop/catalog/": "shop_catalog",
    //     "!/shop/product/:product": "shop_product",
    //     "!/shop/cart": "shop_cart",
    //     "!/shop/wishlist": "shop_wishlist",
    //     "!/shop/compare": "shop_compare",
    //     "!/shop/tracking/(:id)": "shop_tracking"
    //     // "!/shop/profile/orders": "shop_profile_orders"
    // };

    // var staticUrls = {};
    // var staticSettings = {};

    var Router = Backbone.View.extend({

        settings: null,

        // routes: routes,
        urls: {},

        // urls: _(routes).invert(),
        beforeInitialize: function (callback, options) {
            var that = this;
            this.urls = options && options.urls || {};
            var settings = new SiteSettings();
            settings.fetch().done(function () {
                // debugger
                that.settings = settings.toSettings();
                // Router.prototype.settings = staticSettings;
                // Router.prototype.settings._user = {
                that.settings._user = {
                    activeCurrency: ViewWidgetExchangeRates.getActiveCurrencyName(
                        that.settings.MISC.SiteDefaultPriceCurrencyType && that.settings.MISC.SiteDefaultPriceCurrencyType,
                        !!that.settings.MISC.ShowSiteCurrencySelector)
                }

                order.fetch();

                // console.log('shop settings ready: calling callback');
                callback();
                // console.log('finished loading shop');

                Backbone.on('changed:plugin-shop-currency', function (currencyName) {
                    that.settings._user.activeCurrency = currencyName;
                });
            });
        },

        initialize: function () {
            _.bindAll(this, 'setActiveAddress');
        },
        // initialize: function (options) {
        //     debugger
        //     // var self = this;


        //     // this.on('created', function () {

        //     //     SiteMenu({
        //     //         order: order
        //     //     });

        //     //     SiteWidgets({
        //     //         order: order
        //     //     });

        //     //     order.fetch();
        //     // });
        //     order.fetch();
        //     // addr.collection.fetch({reset: true});



        //     // APP.Sandbox.eventSubscribe('global:page:index', function () {
        //     //     self.home();
        //     // });

        //     // APP.Sandbox.eventSubscribe('plugin:shop:offers:get', function () {
        //     //     self.offers();
        //     // });
        // },

        offers: function () {
            // APP.Sandbox.eventNotify('global:content:render', {
            //     name: 'ShopOffers',
            //     el: $('<h1>Offers Goes here</h1>')
            // });
        },

        // home: function () {
        //     APP.getCustomer().setBreadcrumb();
        //     // require([''], function () {
        //         var pageHome = new PageHome();
        //         pageHome.render();
        //         // APP.injectHtml('ShopHome', pageHome.el);
        //     // });

        // },
        menuItemCart: function () {
            var menuCart = new ViewMenuItemCart({
                model: order
            });
            return menuCart;
        },
        menuItemWishList: function () {
            var menuWishList = new ViewMenuItemWishList();
            menuWishList.collection.fetch();
            return menuWishList;
        },
        menuItemCompareList: function () {
            var menuCompare = new ViewMenuItemCompareList();
            menuCompare.collection.fetch();
            return menuCompare;
        },
        menuItemPopupInfoPayment: function () {
            var menuPayment = new ViewMenuItemPopupInfoPayment();
            menuPayment.render();
            return menuPayment;
        },
        menuItemPopupInfoWarranty: function () {
            var menuWarranty = new ViewMenuItemPopupInfoWarranty();
            menuWarranty.render();
            return menuWarranty;
        },
        menuItemPopupInfoShipping: function () {
            var menuShipping = new ViewMenuItemPopupInfoShipping();
            menuShipping.render();
            return menuShipping;
        },
        widgetAddresses: function () {
            var addr = new ViewWidgetAddresses();
            if (this.settings && this.settings.ADDRESS) {
                addr.collection.set(this.settings.ADDRESS);
            } else {
                addr.collection.fetch({reset: true});
            }
            addr.render();
            return addr;
        },
        widgetExchangeRates: function () {
            var rates = new ViewWidgetExchangeRates();
            if (APP.instances.shop.settings.MISC.ShowSiteCurrencySelector) {
                rates.render();
            }
            return rates;
        },
        widgetTrackOrderButton: function () {
            // inject tracking order
            var orderTrackingButton = new ViewWidgetOrderTrackingButton();
            orderTrackingButton.render();
            return orderTrackingButton;
        },
        widgetCartButton: function () {
            // inject embedded shopping cart
            var cartEmbedded = new ViewWidgetCartEmbedded({model: order});
            cartEmbedded.render();
            return cartEmbedded;
        },
        widgetCatalogBar: function () {
            // catalog navigation panel
            var cBar = new ViewWidgetCatalogBar();
            cBar.model.fetch({reset: true});
            return cBar;
        },
        shopCatalogCategory: function (categoryID) {
            // require([''], function () {
                // create new view
                var listProductCatalog = new ListProductCatalog({
                    categoryID: categoryID
                });
                listProductCatalog.collection.fetch({
                    reset: true
                });
                return listProductCatalog;
                // APP.injectHtml('ShopCatalogBrowse', listProductCatalog.el);
            // });
        },

        shopCatalogCategoryPage: function (categoryID, pageNo) {
            // require([''], function () {
                // create new view
                var listProductCatalog = new ListProductCatalog({
                    categoryID: categoryID
                });
                var _pageNo = parseInt(pageNo, 10);
                if (_pageNo.toString() === pageNo) {
                    listProductCatalog.collection.setFilter('filter_viewPageNum', pageNo);
                }
                listProductCatalog.collection.fetch({
                    reset: true
                });
                return listProductCatalog;
                // APP.injectHtml('ShopCatalogBrowseWithPage', listProductCatalog.el);
            // });
        },

        shopProduct: function (productID) {
            // require([''], function () {
                // create new view
                var viewProductItemFull = new ViewProductItemFull({
                    productID: productID
                });
                viewProductItemFull.model.fetch();
                return viewProductItemFull;
                // APP.injectHtml('ShopProduct', viewProductItemFull.el);
            // });
        },

        shopCompare: function () {
            // APP.getCustomer().setBreadcrumb();
            // require([''], function () {
                // create new view
                var listProductCompare = new ListProductCompare();
                listProductCompare.render();
                return listProductCompare;
                // APP.injectHtml('ShopCompareList', listProductCompare.el);
            // });
        },

        shopCart: function () {
            // APP.getCustomer().setBreadcrumb();
            // require([''], function () {
                // debugger;
                var plgAccount = APP.instances.account;
                var accountModel = null;
                if (plgAccount) {
                    accountModel = plgAccount.constructor.account;
                    accountModel.on('change', function () {
                        console.log('account model changed');
                        if (accountModel.has('ID'))
                            order.set('account', accountModel.toJSON());
                        else
                            order.unset('account');
                    });
                }
                // var accountModel = Cache.getObject('account:model');
                // APP.Sandbox.eventSubscribe('plugin:account:model:change', function (accountModel) {
                //     // debugger;
                //     if (accountModel.has('ID'))
                //         order.set('account', accountModel.toJSON());
                //     else
                //         order.unset('account');
                // });
                if (accountModel && accountModel.has('ID')) {
                    console.log('account model has data');
                    order.set('account', accountModel.toJSON());
                }
                // create new view
                var cartStandalone = new CartStandalone({
                    model: order
                });
                // cartStandalone.collection.fetch({merge:true});
                cartStandalone.render();
                return cartStandalone;
                // APP.injectHtml('ShopCart', cartStandalone.el);
            // });
        },

        shopWishlist: function () {
            // APP.getCustomer().setBreadcrumb();
            // require([''], function () {
                // create new view
                var listProductWish = new ListProductWish();
                listProductWish.render();
                return listProductWish;
                // APP.injectHtml('ShopWishList', listProductWish.el);
            // });
        },

        shopTracking: function (orderHash) {
            // APP.getCustomer().setBreadcrumb();
            // require([''], function () {
                // create new view
                var trackingStatus = new TrackingStatus();
                trackingStatus.setOrderHash(orderHash);
                return trackingStatus;
                // APP.injectHtml('ShopTrackOrder', trackingStatus.el);
            // });
        },

        setActiveAddress: function (addr) {
            this.settings._activeAddress = addr;
        }
        //
        // shop_profile_orders: function () {
        //     APP.getCustomer().setBreadcrumb();
        //     if (!Site.hasPlugin('account') || !Auth.getUserID()) {
        //         Backbone.history.navigate("", true);
        //         return;
        //     }
            // require([''], function () {
        //         // Cache.withObject('ProfileOrders', function (cachedView) {
        //         // debugger;
        //         // remove previous view
        //         // if (cachedView && cachedView.remove)
        //         //     cachedView.remove();

        //         // create new view
        //         var profileOrders = new ProfileOrders();
        //         // view.setPagePlaceholder(profileOrders.$el);
        //         profileOrders.fetchAndRender({
        //             profileID: Cache.getObject('AccountProfileID')
        //         });

        //         APP.Sandbox.eventNotify('plugin:account:profile:show', profileOrders.$el);

        //         // return view object to pass it into this function at next invocation
        //         // return profileOrders;
        //         // });
        //     });
        // }

    });

    return Router;
});