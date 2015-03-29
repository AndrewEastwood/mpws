define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/shop/site/hbs/menuCatalog.hbs'
], function (Backbone, Handlebars, Utils, tpl) {

    var MenuCatalog = Backbone.View.extend({
        // tagName: 'a',
        // attributes: {
        //     href: 'javascript://'
        // },
        className: 'dropdown shop-dropdown-catalog',
        id: 'shop-dropdown-catalog-ID',
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return MenuCatalog;

});