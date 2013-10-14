/* Simple native bridge to 3dp template engine
 * --------
 */

APP.Modules.register("lib/templateEngine", [
    /* import globals */
    window

], [
    'lib/handlebars',
    'lib/mpws.api',
], function (wnd, app, Sandbox, Handlebars, mpwsAPI) {

    var _templateCache = {};

    Handlebars.getTemplate = function (templateUrl) {
        return _templateCache[templateUrl] || null;
    }

    Handlebars.hasTemplate = function (templateUrl) {
        return !!_templateCache[templateUrl];
    }

    Handlebars.setTemplate = function (templateUrl, templateHtml) {
        _templateCache[templateUrl] = templateHtml;
    }

    Handlebars.clearCache = function () {
        _templateCache = {};
    }

    return Handlebars;

});