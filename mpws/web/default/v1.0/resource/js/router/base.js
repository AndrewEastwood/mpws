// it is the base page router

APP.Modules.register("router/base", [
    /* import globals */
    window

], [

    /* import dep packages */
    'lib/app.router',
    'view/internal.common'

    /* component implementation */
], function(wnd, app, Sandbox, Router) {

    var BaseRouter = Router.extend({
        name: 'RouterBase'
    });

    return BaseRouter;
});