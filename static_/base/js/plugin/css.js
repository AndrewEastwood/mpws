'use strict';
define(function (){
    var css = {
        load: function (name, parentRequire, onLoad, config) {
            if (config.isBuild) {
                onLoad();
                return;
            }
            var link = document.createElement('link');
            link.type = 'text/css';
            link.rel = 'stylesheet';
            link.href = parentRequire.toUrl(name);
            document.getElementsByTagName('head')[0].appendChild(link);
            onLoad();
        }
    }
    return css;
});