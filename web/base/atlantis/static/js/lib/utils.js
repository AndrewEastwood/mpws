define("default/js/lib/utils", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone'
    /* component implementation */
], function ($, _, Backbone) {

    function Utils() {}

    // cross-browser log function
    function log(s) {
        var args = [].slice.call(arguments);
        var isDebugMsg = (args.length >= 2 && typeof args[0] === 'boolean');

        if (isDebugMsg && !_app.Page.isDebugEnabled())
            return;

        if (isDebugMsg)
            args.shift();

        var msg = args.join(" ");

        if (window.console && console.log && !console.log.isDummy) {
            if (document.all) {
                console.log(msg); // Internet Explorer 8+
            } else {
                console.log.apply(console, args); // Firefox, Safari, Chrome
            }
        } else if (window.Debug && Debug.writeln) {
            Debug.writeln(msg); // Internet Explorer 6, 7
        } else if (window.opera && opera.postError) {
            opera.postError(msg); // Opera
        }
    }

    // creates dummy log object to avoid execptions related to console.log access
    if (typeof console === "undefined") {
        console = {};
        console.log = function () {}
        console.log.isDummy = true;
    }

    // append logger fn
    Utils.log = log;

    // source: http://stackoverflow.com/questions/18017869/build-tree-array-from-flat-array-in-javascript
    Utils.getTreeByJson = function (nodes, idKey, parentKey) {
        // app.log(true, 'Utils.getTreeByJson', nodes, idKey, parentKey); // <-- there's your tree
        var map = {},
            node, roots = {};
        // for (var i = 0; i < nodes.length; i += 1) {
        for (var i in nodes) {
            node = nodes[i];
            node.children = {};
            node.childrenCount = 0;
            map[node[idKey]] = node[idKey]; // use map to look-up the parents
            // app.log(true, '--- current node = ', node);
            // app.log(true, '--- current map = ', map);
            // app.log(true, '--- current node[parentKey] = ', node[parentKey]);
            if (!!parseInt(node[parentKey], 10)) {
                if (!nodes[map[node[parentKey]]].children)
                    nodes[map[node[parentKey]]].children = {};
                // app.log(true, '--- adding node into child branch', node, nodes[map[node[parentKey]]]);
                nodes[map[node[parentKey]]].children[node[idKey]] = node;
                nodes[map[node[parentKey]]].childrenCount++;
            } else {
                roots[node[idKey]] = node;
            }
        }
        // app.log(true, 'Utils.getTreeByJson', roots); // <-- there's your tree
        return roots;
    }

    Utils.getTreeByArray = function (nodes, idKey, parentKey) {
        // app.log(true, 'Utils.getTreeByArray', nodes, idKey, parentKey); // <-- there's your tree
        var map = {},
            node, roots = [];
        for (var i = 0; i < nodes.length; i += 1) {
            // for (var i in nodes) {
            node = nodes[i];
            node.children = [];
            map[node[idKey]] = i; //node[idKey]; // use map to look-up the parents
            // app.log(true, '--- current node = ', node);
            // app.log(true, '--- current map = ', map);
            // app.log(true, '--- current node[parentKey] = ', node[parentKey]);
            if (!!parseInt(node[parentKey] !== "0", 10)) {
                if (!nodes[map[node[parentKey]]].children)
                    nodes[map[node[parentKey]]].children = [];
                nodes[map[node[parentKey]]].children.push(node);
            } else {
                roots.push(node);
            }
        }
        // app.log(true, 'Utils.getTreeByArray', roots); // <-- there's your tree
        return roots;
    }

    Utils.ActivateButtonWhenFormChanges = function (form, buttons) {
        $(form).data('form', $(form).serialize());
        return setInterval(function () {
            var current = $(form).serialize();
            if ($(form).data('form') === current)
                $(buttons).addClass('disabled');
            else
                $(buttons).removeClass('disabled');
        }, 300);
    }

    Utils.isCollectionView = function (obj) {
        return !_.isEmpty(obj.collection);
    }

    Utils.isModelView = function (obj) {
        return !_.isEmpty(obj.model) && !Utils.isCollectionView(obj);
    }

    Utils.isView = function (obj) {
        return !Utils.isModelView(obj) && obj instanceof Backbone.View;
    }

    Utils.getHBSTemplateData = function (obj) {
        var _tplData = obj;
        var _tplExtras = null;
        if (Utils.isCollectionView(obj)) {
            _tplData = obj.collection.toJSON();
            _tplExtras = obj.collection.extras || {};
        } else if (Utils.isModelView(obj)) {
            _tplData = obj.model.toJSON();
            _tplExtras = obj.model.extras || {};
        } else if (Utils.isView(obj)) {
            _tplData = obj.options;
            _tplExtras = obj.extras || {};
        }
        // debugger;
        return {
            lang: obj.lang || {},
            // options: APP.options || {},
            // plugins: APP.config.PLUGINS,
            app: {
                // config: APP.options,
                location: {
                    fragment: Backbone.history.fragment,
                    host: location.hostname,
                    protocol: location.protocol,
                    homepage: APP.config.URL_PUBLIC_SCHEME + '://' + APP.config.URL_PUBLIC_HOSTNAME
                }
            },
            data: _tplData,
            extras: _tplExtras,
            queryParams: Utils.isCollectionView(obj) && obj.collection.queryParams || {},
            // displayItems: obj && obj.displayItems || [],
            instances: APP.instances,
            isToolbox: APP.config.ISTOOLBOX,
            appConfig: APP.config
        }
    }

    return Utils;
});