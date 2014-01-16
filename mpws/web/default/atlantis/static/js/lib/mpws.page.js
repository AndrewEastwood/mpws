// it is the base page router

define("default/js/lib/mpws.page", [

    /* import dep packages */
    'cmn_jquery',
    'default/js/lib/underscore',
    // 'default/js/lib/templateEngine',
    // 'default/js/lib/async',
    // 'lib/storage',
    // 'default/js/lib/utils',
    // 'lib/jquery_ui',

    /* component implementation */
], function ($, _) {

    function mpwsPage () {}

    mpwsPage.getPageError = function() {
        mpwsPage.getPageBody('Woohoo!!! Error 404');
    }

    mpwsPage.getPagePlaceholders = function() {
        return {
            header : $('.MPWSPageHeader'),
            body : $('.MPWSPageBody'),
            footer : $('.MPWSPageFooter')
        };
    }

    mpwsPage.getPageBody = function (content, clear) {
        var _body = mpwsPage.getPagePlaceholders().body;
        if (clear)
            $(_body).html('');
        if (content) {
            // stop loading animation
            mpwsPage.pageSetState(mpwsPage.STATE.LOADING, false);
            // append content
            $(_body).append(content);
        }
        return _body;
    }

    mpwsPage.getPageHeader = function () {
        return mpwsPage.getPagePlaceholders().header;
    }

    mpwsPage.getPageFooter = function () {
        return mpwsPage.getPagePlaceholders().footer;
    }

    mpwsPage.setPageState = function (state, showOrHide) {
        if (state === mpwsPage.STATE.LOADING) {
            if (showOrHide)
                mpwsPage.getPagePlaceholders().body.html('').addClass('render-loading');
            else
                mpwsPage.getPagePlaceholders().body.removeClass('render-loading');
        }
    }

    mpwsPage.setElementState = function (el, state, showOrHide) {
        if (!state)
            return;
        if (showOrHide)
            $(el).addClass('render-' + state);
        else
            $(el).removeClass('render-' + state);
    }

    mpwsPage.pageName = function (name) {
        var classNames = $('body').attr('class');

        if (classNames)
            classNames = classNames.split(' ');
        
        // remove all custom page names
        for (var key in classNames)
            if (/^MPWSPage_/.test(classNames[key]))
                $('body').removeClass(classNames[key]);

        $('body').addClass('MPWSPage_' + name || 'default');
    }

    // function _setupDependenciesFn (deps, callback) {
    //     var _dataMap = {};
    //     // var _self = this;

    //     if (!deps)
    //         return callback(null);

    //     for (var templateAccessKey in deps)
    //         (function (key, depItem) {
    //             _dataMap[key] = function (callback) {
    //                 mpwsPage.getTemplate(depItem.url, false, function (err, templateHtml) {
    //                     callback(err, _.extend({}, depItem, {
    //                         template: templateHtml
    //                     }));
    //                 });
    //             }
    //         })(templateAccessKey, deps[templateAccessKey]);

    //     AsyncLib.parallel(_dataMap, function(err, results) {

    //         // app.log(true, "mpwsPage.setupDependencies", err, results);

    //         _(results).each(function(depItem, key) {
    //             if (depItem.type === 'partial')
    //                 mpwsPage.registerPartial(key, depItem.template);
    //             if (depItem.type === 'helper' && _.isFunction(depItem.fn))
    //                 mpwsPage.registerHelper(key, depItem.fn);
    //         });

    //         callback(err, results);
    //     });
    // }

    // mpwsPage.getTemplate = function(templateUrl, deps, callback) {
    //     var _fetchTemplateFn = function (err) {
    //         // just fire callback
    //         if (!templateUrl && _.isFunction(callback))
    //             return callback(err, null);

    //         if (tplEngine.hasTemplate(templateUrl))
    //             callback(err, tplEngine.getTemplate(templateUrl));
    //         else
    //             mpwsPage.requestTemplate(templateUrl, function (templateHtml) {
    //                 tplEngine.setTemplate(templateUrl, templateHtml);
    //                 callback(err, templateHtml);
    //             });
    //     };

    //     if (deps)
    //         _setupDependenciesFn(deps, _fetchTemplateFn);
    //     else
    //         _fetchTemplateFn(null);
    // }

    // mpwsPage.requestTemplate = function (templatePath, callback) {
    //     var _config = app.Page.getConfiguration();
    //     templatePath = templatePath.replace(/\./g, '//').replace('@', '.');
    //     $.get(_config.URL.staticUrlBase + templatePath).success(callback);
    // }

    return mpwsPage;

});