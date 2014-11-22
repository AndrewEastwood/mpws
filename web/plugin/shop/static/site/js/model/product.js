define('plugin/shop/site/js/model/product', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/lib/utils'
], function (Backbone, _, ShopUtils) {

    // debugger;
    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'products',
                id: this.id
            };
            // if (!this.isNew())
            //     _params.id = this.id;
            return APP.getApiLink(_params);
        }//,
        // getFeatures: function (compatibilityList) {
        //     var self = this, features = {};
        //     // debugger;
        //     // var groupName = null;
        //     // debugger;
        //     if (compatibilityList instanceof Backbone.Collection) {
        //         compatibilityList.each(function (model) {
        //             var featuresGroups = model.getFeatures();
        //             // debugger;
        //             _(featuresGroups).each(function (groupFeatures, groupName) {
        //                 features[groupName] = features[groupName] || {};
        //                 // debugger;
        //                 _(groupFeatures).each(function (v, featureName) {
        //                     // debugger;
        //                     features[groupName][featureName] = features[groupName][featureName] || {};
        //                     features[groupName][featureName][model.id] = false;
        //                 });
        //                 // features[groupName][featureName] = features[groupName][featureName] || {};
        //                 // if (!features[groupName]) {
        //                 //     features[groupName] = {};
        //                 //     _(groupFeatures).each(function (compatibilityList, featureName) {
        //                 //         features[groupName][featureName] = false;
        //                 //     });
        //                 // } else {
        //                 //     _(groupFeatures).each(function (v, featureName) {
        //                 //         if (!features[groupName][featureName]) {
        //                 //             features[groupName][featureName] = false;
        //                 //         }
        //                 //     });
        //                 // }
        //             });
        //         })
        //     }

        //     _(this.get('Features')).each(function (groupFeatures, groupName) {
        //         features[groupName] = features[groupName] || {};
        //         _(groupFeatures).each(function (featureName) {
        //             features[groupName][featureName] = features[groupName][featureName] || {};
        //             features[groupName][featureName][self.id] = true;
        //         });
        //     });
        //     // debugger;

        //     return features;
        // }
    });

});