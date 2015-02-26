define("default/js/plugin/css", ['jquery', 'module'], function ($, module){

    'use strict';

    var css = {
        load: function (name, parentRequire, onLoad, config) {
            $("<link>").attr({
                type: "text/css",
                rel: "stylesheet",
                href: parentRequire.toUrl(name)
            }).appendTo($('head'));
            onLoad();
        }
    }

    return css;
});