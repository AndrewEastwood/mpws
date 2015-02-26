define("plugin/search/toolbox/js/router", [
    'default/js/lib/sandbox',
    'jquery',
    'default/js/lib/auth',
    'default/js/lib/backbone'
], function (Sandbox, $, Auth, Backbone) {

    Sandbox.eventSubscribe('global:route', function () {
        if (&& Auth.getUserID())
        require(['plugin/search/toolbox/js/view/searchMenuEmbedded'], function (ViewSearchEmbedded) {
            var searchEmbedded = new ViewSearchEmbedded();
            searchEmbedded.render();
            Sandbox.eventNotify('global:content:render', {
                name: 'MenuLeft',
                el: searchEmbedded.$el,
                prepend: true
            });
        });
    });

});