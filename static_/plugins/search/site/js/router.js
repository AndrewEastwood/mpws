define([
    'jquery',
    'underscore',
    'backbone'
], function ($, _, Backbone) {

    var SearchPlugin = Backbone.View.extend({
        searchData: function (text, onResults) {
            return $.get(APP.getApiLink('search', 'data', {text: tex}), onResults);
        }
    });

    return SearchPlugin;
});