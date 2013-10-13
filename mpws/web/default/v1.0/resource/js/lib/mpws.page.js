// it is the base page router

APP.Modules.register("lib/mpws.page", [
    /* import globals */
    window

], [

    /* import dep packages */
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/mpws.api',
    'lib/templateEngine',
    'lib/async'
    // 'lib/jquery_ui',

    /* component implementation */
], function (wnd, app, Sandbox, $, _, Backbone, mpwsAPI, tplEngine, async) {

    function mpwsPage () {}

    mpwsPage.prototype.getPageError = function() {
        this.getPageBody('Woohoo!!! Error 404');
    }

    mpwsPage.prototype.getPagePlaceholders = function() {
        return {
            header : $('.MPWSPageHeader'),
            body : $('.MPWSPageBody'),
            footer : $('.MPWSPageFooter')
        };
    }

    mpwsPage.prototype.getPageBody = function (content, clear) {
        var _body = this.getPagePlaceholders().body;
        if (clear)
            $(_body).html('');
        if (content) {
            // stop loading animation
            this.pageLoading(false);
            // append content
            $(_body).append(content);
        }
        return _body;
    }

    mpwsPage.prototype.getPageHeader = function () {
        return this.getPagePlaceholders().header;
    }

    mpwsPage.prototype.getPageFooter = function () {
        return this.getPagePlaceholders().footer;
    }

    mpwsPage.prototype.pageLoading = function (showOrHide) {
        if (showOrHide)
            this.getPagePlaceholders().body.html('').addClass('render-loading');
        else
            this.getPagePlaceholders().body.removeClass('render-loading');
    }

    mpwsPage.prototype.pageName = function (name) {
        var classNames = $('body').attr('class');

        if (classNames)
            classNames = classNames.split(' ');
        
        // remove all custom page names
        for (var key in classNames)
            if (/^MPWSPage_/.test(classNames[key]))
                $('body').removeClass(classNames[key]);

        $('body').addClass('MPWSPage_' + name || 'default');
    }
    // TODO: multiple templating support
    // mpwsPage.prototype.setPageContentByTemplate = function (templateMap, templateDataReceiver) {

    // }

    mpwsPage.prototype.setPageContentByTemplate = function (templatePath, templateDataReceiver) {
        var self = this;
        // start loadng animation
        this.pageLoading(true);
        // start fetching and rendering data
        templateDataReceiver(function (error, data) {
            var _injectionFn = function (template) {
                // this is when we want to render data with template
                if (template) {
                    // compile teplate
                    templateFn = tplEngine.compile(template);
                    app.log(tplEngine, data, templateFn);
                    // combine compiled template with data and inject into page body
                    self.getPageBody(templateFn(data), true);
                } else {
                    // otherwise just overwrite page body content
                    self.getPageBody($('<pre>').text(JSON.stringify(data, null, 4)), true);
                }
            }
            // get template and render data
            if (templatePath)
                mpwsAPI.requestTemplate(templatePath, _injectionFn);
            else
                _injectionFn();
        });
    }


    return mpwsPage;

});