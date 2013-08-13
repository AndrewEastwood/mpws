/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/*
    mpws loader module
*/

window.mpws || (function(window, document){

    var
        // create the main public object
        _qB = {},
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
        qB.log(true, 'page is loaded fn');
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

            var listenerHash = _qB.Utils.hashCode(listener);
            var alreadyAdded = false;
            // avoid duplicates
            for (var i = 0, len = this._events[eventID].length; i < len && !alreadyAdded; i++)
                alreadyAdded = (this._events[eventID][i].id === listenerHash);

            // add another listener
            if (!alreadyAdded) {
                qB.log(true, 'adding subscriber on ', eventID);
                this._events[eventID].push({
                    id : listenerHash,
                    fn : listener
                });
                return true;
            } else 
                qB.log(true, 'this subscriber is already added on ', eventID);
            return false;
        },
        // remove callback subscription to eventID 
        eventUnsubscribe : function (eventID, listener) {
            if (!this._events[eventID])
                return false;
            var listenerHash = _qB.Utils.hashCode(listener);
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
            qB.log(true, 'eventNotify: >>>>>>>>>>>>> ', eventID, ' >>>>>>>>> ');
            qB.log(true, listeners);
            qB.log(true, 'eventNotify: <<<<<<<<<<<<< ', eventID, ' <<<<<<<<< ');
            // loop through listeners
            for (var i = 0, len = this._events[eventID].length; i < len; i++)
                results.push(listeners[i].fn(data));
            // adjust result
            if (results.length == 1)
                rez = results.pop();
            else
                rez = results;
            qB.log(true, 'eventNotify: has result', rez, results);
            // perform callback with results
            if (typeof callback === "function")
                callback(null, rez);
            return rez;
        }
    };

    // Module
    var _Modules = {
        register : function (id, globals, deps, callback) {
            qB.log(true, 'doing register >>>>> ', id, ' with >>>>>> ', deps);
            define(id, filterDownloadedPackages(deps), function () {
                qB.log(true, '::: DEFINE CALLBACK: ', id);
                var fn = _qB.Utils.lockOnFn(callback, globals.concat(_qB, _Sandbox));
                return fn.apply(null, [].slice.call(arguments));
            });
        },
        require : function (packages, callback) {
            if (callback)
                require(filterDownloadedPackages(packages), function () {
                    qB.log(true ,'::: REQUIRE CALLBACK: ', packages);
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
        // qB.log(appConfig);

        // deep copy into qB scope 
        _configuration = appConfig;

        // configure requirejs
        var _rConfig = {
            baseUrl: _configuration.URL.staticUrl + (_qB.Page.isDebugEnabled() ? 'js' : 'build'),
            paths: {
                // general paths
                lib: 'libs',
                router: 'routers',
                widget: 'widgets',
                model: 'models',
                view: 'views'
            }
        };

        if (_qB.Page.isDebugEnabled())
            _rConfig.urlArgs = "bust=" + (new Date()).getTime();

        _rConfig.paths["lib/jquery"] = 'libs/jquery-1.9.1';
        _rConfig.paths["lib/jquery_ui"] = 'libs/jquery-ui-1.10.3.custom';
        
        require.config(_rConfig);

        _Modules.require(['router/base'], function(pageRouter) {
            // start page routing
            pageRouter.start(appConfig);
            pageRouter.onPageLoaded(_self, _initializeLoader);
        });
    }

    // Page object
    _qB.Page = {
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

    _qB.Utils = {
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
        }
    };

    // obfuscate private object variables
    // _qB.S = _Sandbox;
    _qB.Modules = _qB.Utils.exposeToPublic(_Modules)
    _qB.Sandbox = _qB.Utils.exposeToPublic(_Sandbox);
    _qB.Internal = Internal;

    function filterDownloadedPackages (modules) {
        qB.log(true, 'filterDownloadedPackages', modules);
        // var specified = require.s.contexts._.specified;
        return modules;
    }

    // cross-browser log function
    function log (s) {
        var args = [].slice.call(arguments);
        var isDebugMsg = (args.length >= 2 && typeof args[0] === 'boolean');

        if (isDebugMsg && !_qB.Page.isDebugEnabled())
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
    _qB.log = log;

    // expose into global scope
    window.qB = _qB;

})(window, document)