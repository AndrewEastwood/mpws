APP.Modules.register("p_router/plugin", [], [
    "lib/app.router"
], function (app, Sandbox, Router) {


    var ShopRouter = Router.extend({
        name: "RouterPluginShop",
        useUrlQuery: true,
        modulePageMap: [{
            match: ["plugin=shop\&display=currency"],
            deps: ["p_view/internal.currency"]
        }]
    })




    return ShopRouter;

});