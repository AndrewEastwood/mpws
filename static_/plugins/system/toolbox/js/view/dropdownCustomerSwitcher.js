define([
    'jquery',
    'backbone',
    'handlebars',
    'underscore',
    'utils',
    'plugins/system/toolbox/js/collection/listCustomers',
    /* template */
    'text!plugins/system/toolbox/hbs/customerSwitcher.hbs',
    /* lang */
    'i18n!plugins/system/toolbox/nls/translation',
    'select2'
], function ($, Backbone, Handlebars, _, Utils, CollCustomers, tpl, lang) {

    var CustomerSwitcher = Backbone.View.extend({
        tagName: 'li',
        className: 'plugin-system-customer-switcher',
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.collection = new CollCustomers();
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function () {
            var that = this;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // this.$(".js-example-basic-single").select2();
            this.$(".js-data-example-ajax").select2({
                width: '200px',
                ajax: {
                    url: this.collection.url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        // debugger
                        return {
                            _pSearch: params.term, // search term
                        };
                    },
                    // data: function (params) {
                    //     debugger
                    //     var queryParameters = {
                    //         q: params.term
                    //     }

                    //     return queryParameters;
                    // },
                    results: function (data, page) {
                        // debugger
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: _(data.items).map(function (customerItem) {
                                return {
                                    id: customerItem.ID,
                                    text: customerItem.HostName
                                };
                            })
                        };
                    },
                    cache: true
                },
                initSelection: function(element, callback) {
                    callback({id: APP.config.ACTIVEID, text: APP.config.ACTIVEHOSTNAME });
                },
                minimumInputLength: 1
            });
            this.$(".js-data-example-ajax").val(APP.config.ACTIVEID);
            this.$(".js-data-example-ajax").on('change', function (event) {
                // debugger
                var selectedCustomerID = parseInt(event.val, 10);
                var jqxhr = $.ajax({
                    type: 'POST',
                    url: that.collection.url,
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    data: JSON.stringify({
                        switchto: event.val
                    })
                });
                jqxhr.then(function (response) {
                    if (response && selectedCustomerID === response.ID) {
                        location.reload();
                    }
                });
            });
            return this;
        }
    });

    return CustomerSwitcher;
});