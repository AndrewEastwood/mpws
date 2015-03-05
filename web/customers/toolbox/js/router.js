define([
    'jquery',
    'underscore',
    'auth',
    'cachejs',
    'hbs!base/hbs/animationFacebook',
    'bootstrap'
], function ($, _, Auth, Cache, tplFBAnim) {

    APP.dfd.customerReady = new $.Deferred();

    if (!APP.hasPlugin('system')) {
        throw 'System plugin is unavailable';
    }

    var _ifNotAuthorizedNavigateToSignin = function () {
        if (!Auth.getUserID()) {
            // APP.xhrAbortAll();
            if (!/!\/signin/.test(Backbone.history.getHash())) {
                Backbone.history.fragment = false;
                window.location.href = '/#!/signin';
                // Backbone.history.navigate('signin', true);
                // window.location.reload();
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
                'class': 'dropdown menu-' + pluginName,
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
                'class': 'well dashboard-container dashboard-container-' + pluginName,
                rel: 'menu'
            });
            $blockItem.html(tplFBAnim());
            blocks.push($blockItem);
        });
        return blocks;
    }

    // Auth.on('signout:ok', function () {
    //     // debugger
    //     _ifNotAuthorizedNavigateToSignin();
    // });

    // // when user opens signin page and it's already signed in we redirect 
    // // user to home page
    // Auth.on('signin:ok', function () {
    //     // debugger
    //     Backbone.history.navigate(Cache.getFromLocalStorage('location') || '', true);
    // });

    Auth.on('registered', function () {
        // debugger
        Backbone.history.navigate(Cache.getFromLocalStorage('location') || '', true);
    });

    Auth.on('guest', function () {
        // debugger
        _ifNotAuthorizedNavigateToSignin();
    });

    // verify user with every route
    APP.Sandbox.eventSubscribe('global:route', function () {
        _ifNotAuthorizedNavigateToSignin();
    });

    // APP.Sandbox.eventSubscribe('global:auth:status:active', function (data) {
    // });

    // APP.Sandbox.eventSubscribe('global:auth:status:inactive', function () {
    // });

    APP.Sandbox.eventSubscribe('global:page:index', function () {
        // debugger
        APP.Sandbox.eventNotify('global:content:render', {
            name: 'CommonBodyCenter',
            el: $('<div>').addClass('dashboard').html(renderDashboardPlaceholdersFn())
        });
    });

    // function CustomerClass () {}

    // CustomerClass.waitPlugins = true;

    // return CustomerClass;
    $('head title').text(APP.config.TITLE);
    $('a.navbar-brand').attr('href', '/#!/').html(APP.config.TITLE);
    $('a.mpjs-opensite').attr('href', APP.config.URL_PUBLIC_HOMEPAGE).html(APP.config.URL_PUBLIC_HOMEPAGE);
    $('#navbar [name="TopMenuLeft"]').empty().append(renderMenuPlaceholdersFn());

    // Auth.getStatus();

});