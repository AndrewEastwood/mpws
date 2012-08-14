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
        onload: $(document).ready
    };
    
    mpws.module = new function () {
        var _modules = {};
        this.define = function (name, obj) {
            if (!_modules.hasOwnProperty(name))
                _modules[name] = obj;
            
            mpws.tools.log(_modules);
        };
        this.get = function (name) {
            return _modules[name];
        };
        this.modify = function (name, newObject) {
            _modules[name] = newObject;
        }
    }

})(window, document, jQuery, (window.mpws = window.mpws || {})); 
