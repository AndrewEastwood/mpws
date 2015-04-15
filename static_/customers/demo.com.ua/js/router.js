define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'echo',
    'bootstrap-dialog',
    // page templates
    // 'text!./../hbs/breadcrumb.hbs',
    // 'text!./../hbs/homeFrame.hbs',
    // 'text!./../hbs/productsTab.hbs',
    // 'text!./../hbs/viewedProducts.hbs',
    // 'text!./../hbs/page404.hbs',
    // 'text!./../hbs/catalogBrowser.hbs',
    // 'text!./../hbs/categoriesRibbon.hbs',
    // 'text!./../hbs/productComparisons.hbs',
    // 'text!./../hbs/productWishlist.hbs',
    // 'text!./../hbs/search.hbs',
    // 'owl.carousel',
    'bootstrap',
    'icheck',
    'isotope'
], function ($, _, Backbone, Handlebars, echo, BootstrapDialog


 ) {

    var shopRoutes = {
        // '!/': 'home',
        '!/catalog/:category': 'shopCatalogCategory',
        '!/catalog/:category/:page': 'shopCatalogCategoryPage',
        '!/catalog/': 'home', //catalog
        '!/product/:product': 'shopProduct',
        '!/cart': 'shopCart',
        '!/wishlist': 'shopWishlist',
        '!/compare': 'shopCompare',
        '!/tracking/(:id)': 'shopTracking',
        '!/search/:text': 'shopSearch'
        // "!/shop/profile/orders": "shop_profile_orders"
    };

    APP.configurePlugins({
        shop: {
            urls: _(shopRoutes).invert()//,
            // productShortClassNames: 'no-margin product-item-holder hover'
        }
    });

    function onAppReady () {
        function eborLoadIsotope(){
            var $container = $('#container'),
                isotopeOptions = {},
                defaultOptions = {
                   filter: '.home',
                   sortBy: 'original-order',
                   sortAscending: true,
                   layoutMode: 'masonry'
                };
            $container.isotope({
                itemSelector : '.element',
                resizable: false,
                masonry: { columnWidth: $container.width() / 12 }
            });
            $(window).smartresize(function(){
                $container.isotope({
                    masonry: { columnWidth: $container.width() / 12 }
                });
            });
         
            var $optionSets = $('#options').find('.option-set'),
                isOptionLinkClicked = false;
            function changeSelectedLink( $elem ) {
                $elem.parents('.option-set').find('.selected').removeClass('selected');
                $elem.addClass('selected');
            }
         
            $optionSets.find('a[href^="#filter"]').click(function(){
                var $this = $(this);
                if ( $this.hasClass('selected') ) {
                    return;
                }
                changeSelectedLink( $this );
                var href = $this.attr('href').replace( /^#/, '' ),
                    option = jQuery.deparam( href, true );
                jQuery.extend( isotopeOptions, option );
                jQuery.bbq.pushState( isotopeOptions );
                isOptionLinkClicked = true;
                return false;
            });
           var hashChanged = false;
            $(window).bind( 'hashchange', function( event ){
                var hashOptions = window.location.hash ? jQuery.deparam.fragment( window.location.hash, true ) : {},
                   aniEngine = hashChanged ? 'best-available' : 'none',
                   options = jQuery.extend( {}, defaultOptions, hashOptions, { animationEngine: aniEngine } );
                $container.isotope( options );
                isotopeOptions = hashOptions;
                if ( !isOptionLinkClicked ) {
                    var hrefObj, hrefValue, $selectedLink;
                    for ( var key in options ) {
                        hrefObj = {};
                        hrefObj[ key ] = options[ key ];
                        hrefValue = jQuery.param( hrefObj );
                        $selectedLink = $optionSets.find('a[href="#' + hrefValue + '"]');
                        changeSelectedLink( $selectedLink );
                    }
                }
                isOptionLinkClicked = false;
                hashChanged = true;
            }).trigger('hashchange');
        }
        /**
        * CALL ISOTOPE DEPENDING ON FLEXSLIDER Existance
        */
        if ( $('.flexslider')[0] ) {
            $('.flexslider').flexslider({
                animation: "slide",
                start: function(slider){
                    eborLoadIsotope();
                }
            });
        } else {
            eborLoadIsotope();
        }
        // $('form').submit(function(){
        setTimeout(function(){
        //$('#container').isotope('reLayout');
        }, 1000);
        // });
    }


    var Router = Backbone.Router.extend({

        name: 'demo.com.ua',
        
        routes: _.extend.apply(_, [
            {
                '': 'home',
                '!': 'home',
                '!/': 'home',
                ':whatever': 'page404'
            },
            shopRoutes
        ]),

        plugins: {},

        initialize: function () {
            this.on('app:ready', onAppReady);
        },

        home: function () {},

        page404: function () {}

    });

    return Router;



});