define("default/js/plugin/css", ['module'], function (qB, Sandbox, module){

    'use strict';

    function loadCss(url) {
        var link = document.createElement("link");
        link.type = "text/css";
        link.rel = "stylesheet";
        link.href = url;
        document.getElementsByTagName("head")[0].appendChild(link);
    }

    var css = {
        load: function (name, parentRequire, onLoad, config) {
            loadCss(parentRequire.toUrl(name));
            onLoad();
        }
    }

    return css;
});