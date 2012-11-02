/* MPWS Core
 * ---------
 */
(function (window, document, $, mpws) {

    mpws.tools = {
        extend : function (object1, object2) {
            var _obj3 = object1;
            for (var oi in object)
                _obj3[oi] = object2[oi];
            return _obj3;
        },
        log: function (value) {
            console.log(value);
        },
        empty: function (value) {
            return (!!!value)? 0 : 1;
        },
        valueSelect: function (value, defaultValue) {
            if (this.empty(value))
                return defaultValue;
            return value;
        },
        random: function () {
            return Math.floor(Math.random()*Math.pow(2,56)).toString(36);  
        },
        parseUrl: function (url) {
            var _host = url.replace('//www.','//').split('://')[1].split('.')[0];
            return {
                host: _host
            };
        },
        getDailyString: function (strings) {
            var d = new Date();
            if (strings[d.getDay()] !== undefined)
                return strings[d.getDay()];
            return '';
        },
        getObjectCount: function (obj) {
            if ($.isArray(obj))
                return obj.length;
            var _props = 0;
            for (propin in obj)
                _props++;
            return _props;
        },
        importLibrary: function (name, isUrl) {
            var _libUrl = false;
            if (isUrl)
                _libUrl = name;
            else
                _libUrl = "/static/" + name + ".js";
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.src = _libUrl;
            $("head").append(s);
        },
        getRandom: function () {
            return Math.floor(Math.random()*Math.pow(2,56)).toString(36);
        },
        roundNumber: function (num, dec) {
            var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
            return result;
        },
        arraySumm: function (arr, startIdx, endIdx) {
            var s = 0;
            var ii = 0;
            if (typeof(endIdx) === 'undefined')
                endIdx = arr.length;
            for (var i in arr) {
                if (startIdx <= ii && ii <=endIdx)
                    s += arr[i];
                ii++;
            }
            return s;
        },
        onload: $(document).ready
    };
    
    mpws.module = new function () {
        var _modules = {};
        this.define = function (name, obj) {
            if (!_modules.hasOwnProperty(name))
                _modules[name] = obj;
            //mpws.tools.log(_modules);
        };
        this.get = function (name) {
            return _modules[name];
        };
        this.modify = function (name, newObject) {
            _modules[name] = newObject;
        }
    }
    
    mpws.loader = new function () {
        var _postScripts = [];
        this.addPostScript = function(fn) {
            _postScripts.push(fn);
        };
        this.processAll = function () {
            _postScripts.reverse();
            //mpws.tools.log(_postScripts);
            while((fn = _postScripts.pop()) != undefined)
                fn(mpws.page);
        }
    }

})(window, document, jQuery, (window.mpws = window.mpws || {})); 
