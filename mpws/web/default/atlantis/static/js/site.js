define("default/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    // views + models
    'default/js/view/menu',
    'default/js/view/breadcrumb'
], function (Sandbox, $, _, Backbone, Cache, Menu, Breadcrumb) {

    var Site = function (options) {

        var _views = {};
        var _placeholders = _.extend({}, options.placeholders || {});

        // init common views
        if (options && options.views && options.views.menu)
            _views.menu = new Menu(options.views.menu);

        if (options && options.views && options.views.breadcrumb)
            _views.breadcrumb = new Breadcrumb(options.views.breadcrumb);

        $.xhrPool = [];
        $.xhrPool.abortAll = function() {
            $(this).each(function(idx, jqXHR) {
                jqXHR.abort();
            });
            $.xhrPool.length = 0
        };

        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                $.xhrPool.push(xhr);
            },
            complete: function(jqXHR) {
                var index = $.xhrPool.indexOf(jqXHR);
                if (index > -1) {
                    $.xhrPool.splice(index, 1);
                }
            }
        });

        // Sandbox message handler
        $('body').on('click', '[data-action]', function () {
            // debugger;
            Sandbox.eventNotify($(this).data('action'), $(this).data());
        });

        return {
            config: app.config,
            options: options,
            views: _views,
            setConfig: function (config) {
                _config = config;
            },
            start: function () {
                // debugger;
                if (_views.menu)
                    _views.menu.render();

                if (options.site && options.site.title)
                    $('head title').text(options.site.title);
            },
            showBreadcrumbLocation: function (options) {
                if (_views.breadcrumb)
                    _views.breadcrumb.fetchAndRender(options);
                return false;
            },
            addMenuItem: function (item, prepend) {
                if (_views.menu)
                    _views.menu.addMenuItem(item);
                return false;
            },
            getPlaceholder: function (name) {
                return _placeholders[name];
            },
            setPlaceholder: function (name, content) {
                var _ph = this.getPlaceholder(name);
                if (_ph instanceof $ && _ph.length)
                    _ph.html(content);
            },
            addWidgetTop: function (content, prepend) {
                if (prepend)
                    $('.MPWSWidgetsTop').prepend(content);
                else
                    $('.MPWSWidgetsTop').append(content);
            },
            addWidgetBottom: function (content, prepend) {
                if (prepend)
                    $('.MPWSWidgetsBottom').prepend(content);
                else
                    $('.MPWSWidgetsBottom').append(content);
            }
        }
    }


    return Site;
});