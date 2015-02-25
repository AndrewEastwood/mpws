define("customer/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/auth',
    'default/js/lib/cache',
    'default/js/plugin/hbs!default/hbs/animationFacebook',
    'default/js/lib/bootstrap'
], function (Sandbox, $, _, Auth, Cache, tplFBAnim) {

    APP.dfd.customerReady = new $.Deferred();

    if (!APP.hasPlugin('system')) {
        throw "System plugin is unavailable";
    }

    var _ifNotAuthorizedNavigateToSignin = function () {
        if (!Auth.getUserID()) {
            // APP.xhrAbortAll();
            if (!/!\/signin/.test(Backbone.history.getHash())) {
                Backbone.history.fragment = false;
                window.location.href = '/#!/signin';
                // Backbone.history.navigate('signin', true);
                window.location.reload();
            }
        }
    }

    var renderMenuPlaceholdersFn = function () {
        // create containers for the rest plugins
        var menus = [], $menuItem;
        _(APP.config.PLUGINS).each(function (pluginName) {
            $menuItem = $('<li>').attr({
                name: 'MenuForPlugin_' + pluginName,
                id: 'menu-' + pluginName + '-ID',
                "class": 'dropdown menu-' + pluginName,
                rel: 'menu'
            });
            menus.push($menuItem);
        });
        return menus;
    };

    var renderDashboardPlaceholdersFn = function () {
        // create containers for the rest plugins
        var blocks = [], $blockItem;
        _(APP.config.PLUGINS).each(function (pluginName) {
            $blockItem = $('<div>').attr({
                name: 'DashboardForPlugin_' + pluginName,
                id: 'dashboard-container-' + pluginName + '-ID',
                "class": 'well dashboard-container dashboard-container-' + pluginName,
                rel: 'menu'
            });
            $blockItem.html(tplFBAnim());
            blocks.push($blockItem);
        });
        return blocks;
    }

    Sandbox.eventSubscribe('global:page:signout', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('global:page:signin', function () {
        if (Auth.getUserID()) {
            Backbone.history.navigate("", true);
        }
    });

    // Sandbox.eventSubscribe('global:route', function () {
    //     _ifNotAuthorizedNavigateToSignin();
    // });

    Sandbox.eventSubscribe('global:auth:status:active', function (data) {
        Backbone.history.navigate(Cache.getFromLocalStorage('location') || "", true);
    });

    Sandbox.eventSubscribe('global:auth:status:inactive', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    Sandbox.eventSubscribe('global:page:index', function () {
        // debugger
        Sandbox.eventNotify('global:content:render', {
            name: 'CommonBodyCenter',
            el: $('<div>').addClass('dashboard').html(renderDashboardPlaceholdersFn())
        });
    });

    // function CustomerClass () {}

    // CustomerClass.waitPlugins = true;

    // return CustomerClass;
    $('head title').text(APP.config.TITLE);
    $('a.navbar-brand').attr('href', APP.config.URL_PUBLIC_HOMEPAGE).html(APP.config.TITLE);
    $('#navbar [name="TopMenuLeft"]').empty().append(renderMenuPlaceholdersFn());

    // Auth.getStatus();

});