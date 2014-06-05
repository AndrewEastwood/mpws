define("default/js/plugin/css", ['lib/jquery', 'module'], function ($, module){

    'use strict';

    function loadCss(url) {
        $("<link>").attr({
            type: "text/css",
            rel: "stylesheet",
            hre: url
        }).appendTo($('head'));
    }

    var css = {
        load: function (name, parentRequire, onLoad, config) {
            loadCss(parentRequire.toUrl(name));
            onLoad();
        }
    }

    return css;
});