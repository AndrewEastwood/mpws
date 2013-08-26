/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/*
    APP loader module
*/

window.APP || (function(window, document){

    var
        // create the main public object
        _app = {},
        _self = this,
        // indicates wheter page is in the ready state
        _docIsReady = false,
        // loader init state
        //_loaderInitialized = false,
        // temporary added list to store
        // onDocReady function
        _onDocReadyFns = [],
        _configuration = null;


    function _initializeLoader () {

        // we don't want initialize loader multiple times
        // if (_loaderInitialized)
        //     return false;

        // release onDocReady functions
        for (var i = 0, len = _onDocReadyFns.length; i < len; i++)
            _onDocReadyFns.shift()(_Sandbox);

        // init added modules
        // _Modules.runAll({autoinit : true});

        // set loader states
        _docIsReady = true;
        // _loaderInitialized = true;

        // notify all subscribers
        // _Sandbox.eventNotify("page-loaded");
        _app.log(true, 'page is loaded fn');
    }

    // Sandbox
    var _Sandbox = {
        // list of all page states
        _states : {
            logged : false
        },
        _events : {},
        // get state value by key
        stateGet : function (stateKey) {
            return this._states[stateKey];
        },
        // add/set enabled state
        _stateSetOn : function (stateKey) {
            this._stateSet(stateKey, true);
        },
        // add/set disabled state
        _stateSetOff : function (stateKey) {
            this._stateSet(stateKey, false);
        },
        // set/add statue value
        _stateSet : function (stateKey, stateValue) {
            this._states[stateKey] = stateValue;
        },
        // subscribe on eventID
        eventSubscribe : function (eventID, listener) {
            if (!this._events[eventID])
                this._events[eventID] = [];

            var listenerHash = _app.Utils.hashCode(listener);
            var alreadyAdded = false;
            // avoid duplicates
            for (var i = 0, len = this._events[eventID].length; i < len && !alreadyAdded; i++)
                alreadyAdded = (this._events[eventID][i].id === listenerHash);

            // add another listener
            if (!alreadyAdded) {
                _app.log(true, 'adding subscriber on ', eventID);
                this._events[eventID].push({
                    id : listenerHash,
                    fn : listener
                });
                return true;
            } else 
                _app.log(true, 'this subscriber is already added on ', eventID);
            return false;
        },
        // remove callback subscription to eventID 
        eventUnsubscribe : function (eventID, listener) {
            if (!this._events[eventID])
                return false;
            var listenerHash = _app.Utils.hashCode(listener);
            var unsubcribeCount = 0;
            for (var i = 0, len = this._events[eventID].length; i < len; i++)
                if (this._events[eventID][i].id === listenerHash) {
                    this._events[eventID].splice(i, 1);
                    unsubcribeCount++;
                }
            return unsubcribeCount > 0;
        },
        eventNotify : function (eventID, data, callback) {
            var listeners = this._events[eventID];
            if (!listeners)
                return;
            var results = [];
            var rez = null;
            _app.log(true, 'eventNotify: >>>>>>>>>>>>> ', eventID, ' >>>>>>>>> ');
            _app.log(true, listeners);
            _app.log(true, 'eventNotify: <<<<<<<<<<<<< ', eventID, ' <<<<<<<<< ');
            // loop through listeners
            for (var i = 0, len = this._events[eventID].length; i < len; i++)
                results.push(listeners[i].fn(data));
            // adjust result
            if (results.length == 1)
                rez = results.pop();
            else
                rez = results;
            _app.log(true, 'eventNotify: has result', rez, results);
            // perform callback with results
            if (typeof callback === "function")
                callback(null, rez);
            return rez;
        }
    };

    // Module
    var _Modules = {
        register : function (id, globals, deps, callback) {
            _app.log(true, 'doing register >>>>> ', id, ' with >>>>>> ', deps);
            define(id, filterDownloadedPackages(deps), function () {
                _app.log(true, '::: DEFINE CALLBACK: ', id);
                var fn = _app.Utils.lockOnFn(callback, globals.concat(_app, _Sandbox));
                return fn.apply(null, [].slice.call(arguments));
            });
        },
        require : function (packages, callback) {
            if (callback)
                require(filterDownloadedPackages(packages), function () {
                    _app.log(true ,'::: REQUIRE CALLBACK: ', packages);
                    return callback.apply(null, [].slice.call(arguments));
                });
            else
                require(filterDownloadedPackages(packages));
        },
        downloadPackages : function (packages, callback) {
            _Modules.require(packages, callback);
        }
    };

    // This is a simple container for public objects.
    // Used function instead of a regular object so it won't be displayed by console.log($BV).
    function Internal () {}

    Internal.configureAppLoader = function (appConfig) {
        _app.log(true, appConfig);

        // deep copy into qB scope 
        _configuration = appConfig;

        // configure requirejs
        var _rConfig = _configuration.REQUIREJS.app;
        //     baseUrl: _configuration.REQUIREJS.baseUrl,
        //     paths: {
        //         // general paths
        //         lib: 'libs',
        //         router: 'routers',
        //         widget: 'widgets',
        //         model: 'models',
        //         view: 'views'
        //     }
        // };

        if (_app.Page.isDebugEnabled())
            _rConfig.urlArgs = "bust=" + (new Date()).getTime();

        // _rConfig.paths["lib/jquery"] = 'libs/jquery-1.9.1';
        // _rConfig.paths["lib/jquery_ui"] = 'libs/jquery-ui-1.10.3.custom';
        
        requirejs.config(_rConfig);

        // var _pageOnReadySetupFn = function () {
        //     $(document).ready(function () {
        //         _Sandbox.eventNotify("page-loaded");
        //         app.log(true ,'page loaded');
        //     });
        // }

        // include required routers
        // if (_configuration.REQUIREJS.startupModules)
        //     _Modules.require(_configuration.REQUIREJS.startupModules, _pageOnReadySetupFn);
        // else
        //     _pageOnReadySetupFn();


        // // request base router from main context
        _Modules.require(['router/base'], function(BaseRouter) {
            // start page routing
            var router = new BaseRouter(_configuration.REQUIREJS.router);
            router.start();
            router.onPageLoaded(_self, _initializeLoader);
        });
            // notify all subscribers that page is ready

    }

    // Page object
    _app.Page = {
        getConfiguration: function () {
            return _configuration;
        },
        // store function and run it when page is set into ready state.
        // when page is already inited this will perform given function immediatelly.
        // whenDocumentIsReadyDo : function (fn) {
        OnDocReady : function (fn) {
            if (!_docIsReady) {
                _onDocReadyFns.push(function(){
                    fn(_Sandbox);
                });
            } else
                fn(_Sandbox);
        },
        getUrl : function (getFull, pattern) {
            if (typeof pattern === 'object')
                return this.getUrl(getFull).match(pattern);
            if (getFull)
                return window.location.href;
            return window.location.protocol + '//' + window.location.host + window.location.pathname;
        },
        getVersion : function () {
            return _configuration && _configuration.VERSION;
        },
        getBuildDate : function () {
            return new Date(this.getVersion());
        },
        isDebugEnabled: function () {
            return _configuration && _configuration.STATES && _configuration.STATES.pageDebug
        }
    };

    _app.Utils = {
        hashCode : function(obj){
            var string  = obj.toString();
            var hash = 0, i, char;
            if (string.length === 0) return hash;
            for (i = 0, l = string.length; i < l; i++) {
                char  = string.charCodeAt(i);
                hash  = ((hash<<5)-hash)+char;
                hash |= 0; // Convert to 32bit integer
            }
            return hash;
        },
        exposeToPublic : function (obj) {
            var pub = {};
            for (var fnName in obj) {
                if (/^_/.test(fnName))
                    continue;

                if (typeof obj[fnName] === "function")
                    pub[fnName] = (function (fnToCall){
                        return function () {
                            var args = [].slice.call(arguments);
                            obj[fnToCall].apply(obj, args);
                        }
                    })(fnName);
                else
                    pub[fnName] =  obj[fnName];
            }
                    
            return pub;
        },
        lockOnFn : function (fn, args) {
            return function () {
                return fn.apply(null, args.concat([].slice.call(arguments, 0)));
            }
        },
        isFunction: function( obj ) {
            return _app.Utils.type(obj) === "function";
        },
        isArray: Array.isArray || function( obj ) {
            return _app.Utils.type(obj) === "array";
        },
        isWindow: function( obj ) {
            return obj != null && obj == obj.window;
        },
        isNumeric: function( obj ) {
            return !isNaN( parseFloat(obj) ) && isFinite( obj );
        },
        type: function( obj ) {
            if ( obj == null ) {
                return String( obj );
            }
            return typeof obj === "object" || typeof obj === "function" ?
                class2type[ core_toString.call(obj) ] || "object" :
                typeof obj;
        },
        isPlainObject: function( obj ) {
            // Must be an Object.
            // Because of IE, we also have to check the presence of the constructor property.
            // Make sure that DOM nodes and window objects don't pass through, as well
            if ( !obj || _app.Utils.type(obj) !== "object" || obj.nodeType || _app.Utils.isWindow( obj ) ) {
                return false;
            }

            try {
                // Not own constructor property must be Object
                if ( obj.constructor &&
                    !core_hasOwn.call(obj, "constructor") &&
                    !core_hasOwn.call(obj.constructor.prototype, "isPrototypeOf") ) {
                    return false;
                }
            } catch ( e ) {
                // IE8,9 Will throw exceptions on certain host objects #9897
                return false;
            }

            // Own properties are enumerated firstly, so to speed up,
            // if last one is own, then all properties are own.

            var key;
            for ( key in obj ) {}

            return key === undefined || core_hasOwn.call( obj, key );
        },

        isEmptyObject: function( obj ) {
            var name;
            for ( name in obj ) {
                return false;
            }
            return true;
        },
        extend: function () {
            var src, copyIsArray, copy, name, options, clone,
                target = arguments[0] || {},
                i = 1,
                length = arguments.length,
                deep = false;

            // Handle a deep copy situation
            if ( typeof target === "boolean" ) {
                deep = target;
                target = arguments[1] || {};
                // skip the boolean and the target
                i = 2;
            }

            // Handle case when target is a string or something (possible in deep copy)
            if ( typeof target !== "object" && !_app.Utils.isFunction(target) ) {
                target = {};
            }

            // extend jQuery itself if only one argument is passed
            if ( length === i ) {
                target = this;
                --i;
            }

            for ( ; i < length; i++ ) {
                // Only deal with non-null/undefined values
                if ( (options = arguments[ i ]) != null ) {
                    // Extend the base object
                    for ( name in options ) {
                        src = target[ name ];
                        copy = options[ name ];

                        // Prevent never-ending loop
                        if ( target === copy ) {
                            continue;
                        }

                        // Recurse if we're merging plain objects or arrays
                        if ( deep && copy && ( _app.Utils.isPlainObject(copy) || (copyIsArray = _app.Utils.isArray(copy)) ) ) {
                            if ( copyIsArray ) {
                                copyIsArray = false;
                                clone = src && _app.Utils.isArray(src) ? src : [];

                            } else {
                                clone = src && _app.Utils.isPlainObject(src) ? src : {};
                            }

                            // Never move original objects, clone them
                            target[ name ] = _app.Utils.extend( deep, clone, copy );

                        // Don't bring in undefined values
                        } else if ( copy !== undefined ) {
                            target[ name ] = copy;
                        }
                    }
                }
            }
            // Return the modified object
            return target;
        }
    };

    // obfuscate private object variables
    // _app.S = _Sandbox;
    _app.Modules = _app.Utils.exposeToPublic(_Modules)
    _app.Sandbox = _app.Utils.exposeToPublic(_Sandbox);
    _app.Internal = Internal;

    function filterDownloadedPackages (modules) {
        _app.log(true, 'filterDownloadedPackages', modules);
        return modules;
    }

    // cross-browser log function
    function log (s) {
        var args = [].slice.call(arguments);
        var isDebugMsg = (args.length >= 2 && typeof args[0] === 'boolean');

        if (isDebugMsg && !_app.Page.isDebugEnabled())
            return;

        if (isDebugMsg)
            args.shift();

        var msg = args.join(" ");

        if (window.console && console.log && !console.log.isDummy) {
            if (document.all) {
                console.log(msg);  // Internet Explorer 8+
            } else {
                console.log.apply(console, args);  // Firefox, Safari, Chrome
            }
        } else if (window.Debug && Debug.writeln) {
            Debug.writeln(msg);  // Internet Explorer 6, 7
        } else if (window.opera && opera.postError) {
            opera.postError(msg);  // Opera
        }
    }

    // creates dummy log object to avoid execptions related to console.log access
    if (typeof console === "undefined") {
        console = {};
        console.log = function() {}
        console.log.isDummy = true;
    }

    // append logger fn
    _app.log = log;

    // expose into global scope
    window.APP = _app;

})(window, document)