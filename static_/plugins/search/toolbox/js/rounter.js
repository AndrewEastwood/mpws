define([
    'jquery',
    'auth',
    'backbone'
], function ($, Auth, Backbone) {

    APP.Sandbox.eventSubscribe('global:route', function () {
        // if (&& Auth.getUserID())
        require(['plugins/search/toolbox/js/view/searchMenuEmbedded'], function (ViewSearchEmbedded) {
            var searchEmbedded = new ViewSearchEmbedded();
            searchEmbedded.render();
            APP.Sandbox.eventNotify('global:content:render', {
                name: 'MenuLeft',
                el: searchEmbedded.$el,
                prepend: true
            });
        });
    });

});