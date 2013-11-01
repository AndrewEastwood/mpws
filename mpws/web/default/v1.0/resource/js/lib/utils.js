APP.Modules.register("lib/utils", [
    /* import globals */
    window
], [
    'lib/zepto',
    'lib/underscore'
    /* component implementation */
], function(window, app, Sandbox, $, _) {

    function Utils () {}

    Utils.setTableColumnsWidth = function (config) {

        // container width
        var cntWidth = config.container.width();

        // get static widths
        var staticW = 0;
        var minWidthTotal = 0;
        var maxWidthTotal = 0;
        var _adjustedColumnWidths = {};

        _(config.columns).each(function(columnConfig, columnName){

            // dynamic width
            if (_.isNumber(columnConfig.width))
                _adjustedColumnWidths[columnName] = columnConfig.width;

            // static width
            if (_.isString(columnConfig.width)) {
                staticW += parseInt(columnConfig.width, 10);
                _adjustedColumnWidths[columnName] = columnConfig.width;
            }

            // get all min width to set container min-width
            if (_.isString(columnConfig.minWidth))
                minWidthTotal += parseInt(columnConfig.minWidth, 10);

            // get all max width to set container min-width
            if (_.isString(columnConfig.maxWidth))
                maxWidthTotal += parseInt(columnConfig.maxWidth, 10);
        });

        var freeWidth = cntWidth - staticW;

        // qB.log(true, 'container w: : ', cntWidth, 'min-w:', config.container.css('min-width'), 'max-w', maxWidthTotal);
        // qB.log('static:', staticW, 'free:', freeWidth, 'max', staticW+maxWidthTotal, 'min', staticW+minWidthTotal);
        // qB.log('111111 >>>>>> ', JSON.stringify(_adjustedColumnWidths));

        // calc free space according to column min width
        _(config.columns).each(function(columnConfig, columnName){

            if (_.isString(_adjustedColumnWidths[columnName]))
                return;

            // get dynamic column width using free space
            var dynamicW = _adjustedColumnWidths[columnName] * freeWidth / 100;

            // qB.log('DDDDDynamic width for ', columnName, ' is >>>> ', dynamicW);

            if (_.isString(columnConfig.minWidth)) {

                var columnMinWidth = parseInt(columnConfig.minWidth, 10);
                if (dynamicW < columnMinWidth) {

                    // reduce free space
                    freeWidth -= columnMinWidth;

                    // need to move to another column
                    var dynW = _adjustedColumnWidths[columnName];

                    // and replce dynamic with min-width (static) value
                    _adjustedColumnWidths[columnName] = columnMinWidth + 'px';

                    // increase first dynamic column
                    _(_adjustedColumnWidths).each(function(w, id){
                        if (_.isNumber(w) && dynW) {
                            _adjustedColumnWidths[id] += dynW;
                            dynW = false;
                        }
                    });
                }
            }

            if (_.isString(columnConfig.maxWidth)) {
                var columnMaxWidth = parseInt(columnConfig.maxWidth, 10);

                if (dynamicW >= columnMaxWidth) {
                    // reduce free space
                    freeWidth -= columnMaxWidth;
                    // need to move to another column
                    var dynW = _adjustedColumnWidths[columnName];

                    // and replce dynamic with min-width (static) value
                    _adjustedColumnWidths[columnName] = columnMaxWidth + 'px';

                    // // increase first dynamic column
                    // _(_adjustedColumnWidths).each(function(w, id){
                    //     if (_.isNumber(w) && dynW) {
                    //         _adjustedColumnWidths[id] += dynW;
                    //         dynW = false;
                    //     }
                    // });

                    // increase first dynamic column
                    _(_adjustedColumnWidths).each(function(w, id){
                        if (_.isNumber(w) && dynW) {
                            _adjustedColumnWidths[id] += dynW;
                            dynW = false;
                        }
                    });
                }
            }

        });

        // qB.log(true, '2222 >>>>>> ', JSON.stringify(_adjustedColumnWidths));

        // set column width
        _(_adjustedColumnWidths).each(function(columnW, columnName){
            var $column = $('.' + config.style.cellPrefix + columnName);

            if (_.isNumber(columnW))
                columnW = columnW * freeWidth / 100;

            var _mp = ($column.outerWidth() - $column.width());
            var _nw = parseInt(columnW, 10) - parseInt(_mp, 10);

            $column.width(_nw);
        });

        config.container.css('min-width', staticW + minWidthTotal);

        if (maxWidthTotal)
            config.container.css('max-width', staticW + maxWidthTotal);

        if (config.custom) {

            if (config.custom.attachToWindowResize) {
                config.custom.attachToWindowResize = false;
                // var _cloneConfig = _.extend({}, config, {custom:{attachToWindowResize:false}});
                $(window).resize(function() {
                    Utils.setTableColumnsWidth(config);
                });
            }

            if (config.custom.relatedContainers) {
                $(config.custom.relatedContainers).css('min-width', staticW + minWidthTotal);
                if (maxWidthTotal)
                    $(config.custom.relatedContainers).css('max-width', staticW + maxWidthTotal);
            }

        }

        Sandbox.eventSubscribe("page-loaded", function () {
            // for (var i = 0; i < 101; i += 10)
            //     setTimeout(Utils.setTableColumnsWidth(config), 20 + i);
            Utils.setTableColumnsWidth(config);
        });

    }

    // source: http://stackoverflow.com/questions/18017869/build-tree-array-from-flat-array-in-javascript
    Utils.getTreeByJson = function (nodes, idKey, parentKey) {
        app.log(true, 'Utils.getTreeByJson', nodes, idKey, parentKey); // <-- there's your tree
        var map = {}, node, roots = {};
        // for (var i = 0; i < nodes.length; i += 1) {
        for (var i in nodes) {
            node = nodes[i];
            node.children = {};
            map[node[idKey]] = node[idKey]; // use map to look-up the parents
            app.log(true, '--- current node = ', node);
            app.log(true, '--- current map = ', map);
            app.log(true, '--- current node[parentKey] = ', node[parentKey]);
            if (!!parseInt(node[parentKey], 10)) {
                if (!nodes[map[node[parentKey]]].children)
                    nodes[map[node[parentKey]]].children = {};
                app.log(true, '--- adding node into child branch', node, nodes[map[node[parentKey]]]);
                nodes[map[node[parentKey]]].children[node[idKey]] = node;
            } else {
                roots[node[idKey]] = node;
            }
        }
        app.log(true, 'Utils.getTreeByJson', roots); // <-- there's your tree
        return roots;
    }

    Utils.getTreeByArray = function (nodes, idKey, parentKey) {
        app.log(true, 'Utils.getTreeByArray', nodes, idKey, parentKey); // <-- there's your tree
        var map = {}, node, roots = [];
        for (var i = 0; i < nodes.length; i += 1) {
        // for (var i in nodes) {
            node = nodes[i];
            node.children = [];
            map[node[idKey]] = i;//node[idKey]; // use map to look-up the parents
            app.log(true, '--- current node = ', node);
            app.log(true, '--- current map = ', map);
            app.log(true, '--- current node[parentKey] = ', node[parentKey]);
            if (!!parseInt(node[parentKey] !== "0", 10)) {
                if (!nodes[map[node[parentKey]]].children)
                    nodes[map[node[parentKey]]].children = [];
                nodes[map[node[parentKey]]].children.push(node);
            } else {
                roots.push(node);
            }
        }
        app.log(true, 'Utils.getTreeByArray', roots); // <-- there's your tree
        return roots;
    }

    return Utils;
});