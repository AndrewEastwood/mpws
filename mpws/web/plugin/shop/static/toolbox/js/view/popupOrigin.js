define("plugin/shop/toolbox/js/view/popupOrigin", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/origin',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupOrigin',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, Backbone, ModelOriginItem, ShopUtils, Utils, BootstrapDialog, tpl, lang) {

    var OriginItem = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            // MView.prototype.initialize.call(this);
            var self = this;
            // debugger;
            this.model = new ModelOriginItem();

            this.listenTo(this.model, "sync", function () {
                self.render();
            });
            // this.model.on("request", function () {
            //     debugger;
            // });
            this.model.on("error", function () {
                self.renderError();
            });
        },
        render: function () {
            var self = this;
            var dlg = new BootstrapDialog({
                cssClass: 'shop-toolbox-origin-edit',
                buttons: [{
                    id: "save",
                    label: lang.popup_origin_button_Save,
                    action: function (dialog) {
                        // debugger;
                        var _data = self.$el.find('form').serializeArray();
                        var data = _.object(_(_data).pluck('name'), _(_data).pluck('value'));
                        self.model.set(data);
                        // dialogIsShown = false;
                        // if (self.model.isNew())
                        //     self.model.updateItem(self.options.originID, data);
                        // else
                        self.model.save().done(function(){
                            Sandbox.eventNotify("plugin:shop:origin:item:changed");
                        });
                        dialog.close();
                    }
                }]
            });
            if (self.model.isNew())
                dlg.setTitle(lang.popup_origin_title_new);
            else
                dlg.setTitle(lang.popup_origin_title_edit);

            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            dlg.setMessage(this.$el);

            if (!dlg.isOpened())
                dlg.open();
            // debugger;
            Utils.ActivateButtonWhenFormChanges(self.$('form'), dlg.getButton("save"));
            // self.$('.helper').tooltip();
            self.stopListening();
        },
        renderError: function () {
            BootstrapDialog.error("Unexpected error");
        },
        renderCreate: function () {
            var self = this;
            this.model.fetch();
        },
        renderEdit: function (ID) {
            var self = this;
            this.model.fetch({
                data: {
                    ID: ID
                }
            });
        }
    });

    return OriginItem;

});