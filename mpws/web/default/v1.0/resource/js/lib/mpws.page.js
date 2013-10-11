// it is the base page router

APP.Modules.register("lib/mpws.page", [
    /* import globals */
    window

], [

    /* import dep packages */
    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    // 'lib/jquery_ui',

    /* component implementation */
], function(wnd, app, Sandbox, $, _, Backbone) {

    function mpwsPage () {}

    mpwsPage.prototype.getPageError = function() {
        // body...
        return "404";
    };

    return mpwsPage;

});