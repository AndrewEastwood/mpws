qB.Modules.register("collection/BRowCollectionClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'model/BRowModelClass'

], function(qB, Sandbox, $, _, Backbone, BRowModelClass){

    var BRowCollectionClass = Backbone.Collection.extend({
        customOptions: {
            modelTable: null
        },
        customSort: {
            key : null,
            isAscending: null
        },
        model : BRowModelClass,
        comparator: function(rowA, rowB) {
            // qB.log('BRowCollectionClass >> comparator: ', arguments, 'by ', this.customSort.key)

            var _config = this.getConfig();

            var _sortLastRow = !(_config.custom && _config.custom.sortLastRow === false);

            // qB.log(true, 'BRowCollectionClass >>> comparator', _sortLastRow ? "sort all rows" : " + skip last row");
            // qB.log(true, 'BRowCollectionClass >>> comparator of ', _config.tableName);
            // qB.log(rowA)
            // qB.log(rowB)

            if (this.customSort.key && rowA.get('allowToBeSorted') && rowB.get('allowToBeSorted')) {
                var rowDataA = rowA.get('rawData');
                var rowDataB = rowB.get('rawData');

                // qB.log(true, 'BRowCollectionClass comparator: ', rowA, rowB);

                // get two values to compare
                var _valueToCompareA = rowDataA[this.customSort.key] || null;
                var _valueToCompareB = rowDataB[this.customSort.key] || null;

                var _comparatorRez = 0;

                if (_valueToCompareA < _valueToCompareB)
                    _comparatorRez = 1;

                if (_valueToCompareA > _valueToCompareB)
                    _comparatorRez = -1;

                // inverse result
                if (this.customSort.isAscending)
                    _comparatorRez *= -1;

                return _comparatorRez;
            }
        },
        setSortKey: function (sortKey) {
            this.customSort.key = sortKey;
        },
        setSortDirection: function (isAscending) {
            this.customSort.isAscending = isAscending;
        },
        setCustomOptions: function (customOptions) {
            this.customOptions = _.extend({}, this.customOptions, customOptions);
            // qB.log(true, 'BRowCollectionClass >>> initialize', this.customOptions);
        },
        getConfig: function () {
            return this.customOptions.modelTable && this.customOptions.modelTable.get('config');
        },
    });

    return BRowCollectionClass;

})