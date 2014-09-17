define("plugin/shop/toolbox/js/view/filterPanelOrigins", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/filterPanelOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/filterPanelOrigins',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/buttonMenuOriginListItem',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, _, Backbone, Utils, CollectionOriginsFilter, tpl, tplBtnMenuMainItem, lang) {

    var ManagerContent_Origins = Backbone.View.extend({
        className: 'panel panel-default shop_managerContent_Origins',
        template: tpl,
        lang: lang,
        events: {
            'click #show_removed': 'showRemoved'
        },
        showRemoved: function (event) {
            this.collection.requestData.removed = $(event.target).is(':checked') ? true : null;
            this.collection.fetch({reset: true});
        },
        initialize: function () {
            this.collection = new CollectionOriginsFilter();
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            // debugger;
            // var self = this;
            var _data = Utils.getHBSTemplateData(this);
            _(_data.data).each(function(item) {
                item.contextButton = tplBtnMenuMainItem(Utils.getHBSTemplateData(item));
            });
            this.$el.html(tpl(_data));
            this.$('.dropdown-toggle').addClass('btn-link');
            return this;
        }
    });

    return ManagerContent_Origins;

});