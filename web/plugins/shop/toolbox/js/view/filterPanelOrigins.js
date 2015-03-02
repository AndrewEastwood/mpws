define([
    'sandbox',
    'underscore',
    'backbone',
    'utils',
    'cachejs',
    'bootstrap-dialog',
    'plugins/shop/toolbox/js/collection/filterPanelOrigins',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/filterPanelOrigins',
    'hbs!plugins/shop/toolbox/hbs/buttonMenuOriginListItem',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Sandbox, _, Backbone, Utils, Cache, BootstrapDialog, CollectionOriginsFilter, tpl, tplBtnMenuMainItem, lang) {

    var FilterPanelOrigins = Backbone.View.extend({
        className: 'panel panel-default shop_filterPanelOrigins',
        template: tpl,
        lang: lang,
        events: {
            'click #show_removed': 'showRemoved',
            'click .remove': 'removeOrigin',
            'click .restore': 'restoreOrigin',
            'click .dropdown-toggle': 'dropDownFixPosition',
            'click .add-product': 'addProduct',
            'change input:checkbox.shop-filter-origin': 'toggleProductFilter'
        },
        showRemoved: function (event) {
            this.collection.fetchWithRemoved($(event.target).is(':checked'), {
                reset: true
            });
        },
        initialize: function () {
            this.collection = new CollectionOriginsFilter();
            this.listenTo(this.collection, 'reset', this.render);

            _.bindAll(this, 'saveLayout', 'toggleProductFilter');

            Sandbox.eventSubscribe('global:route', $.proxy(function () {
                clearInterval(this.interval_saveLayout);
            }, this));

        },
        toggleProductFilter: function () {
            // debugger
            var selectedIDs = this.$('.shop-filter-origin:checked').map(function(){return parseInt($(this).attr('id'), 10);}).toArray();
            Cache.set('shopFilterOrdersLayoutRD', {
                selectedOriginsIDs: selectedIDs
            }, true);
            this.trigger('originTree:changed:origin', selectedIDs);
        },
        saveLayout: function () {
            // console.log('saving layout filter origins');
            Cache.set("shopFilterOrdersLayoutRD", {
                scrollTopFilterOrigins: this.$('.filter-list-origins').scrollTop()
            }, true);
        },
        restoreLayout: function () {
            var layoutConfig = Cache.get("shopFilterOrdersLayoutRD");
            layoutConfig = _.defaults({}, layoutConfig || {}, {
                scrollTopFilterOrigins: 0,
                selectedOriginsIDs: []
            });
            this.$('.filter-list-origins').scrollTop(layoutConfig.scrollTopFilterOrigins);
            _(layoutConfig.selectedOriginsIDs).each(function (selectedID) {
                var $cbEl = this.$('.shop-filter-origin[id="' + selectedID + '"]');
                if ($cbEl.length === 1) {
                    $cbEl.prop('checked', true);
                }
            });
            this.interval_saveLayout = setInterval(this.saveLayout, 800);
        },
        removeOrigin: function (event) {
            var id = $(event.target).data('id'),
                model = this.collection.get(id),
                self = this;
            if (!model) {
                return;
            }
            BootstrapDialog.confirm("Видалити цього виробника?", function (rez) {
                if (rez) {
                    model.destroy({
                        success: function () {
                            self.collection.fetch({
                                reset: true
                            });
                        }
                    });
                }
            });
        },
        restoreOrigin: function (event) {
            var id = $(event.target).data('id'),
                model = this.collection.get(id);
            if (!model) {
                return;
            }
            BootstrapDialog.confirm("Відновити цього виробника?", function (rez) {
                if (rez) {
                    model.save({
                        Status: 'ACTIVE'
                    }, {
                        patch: true,
                        success: function () {
                            model.collection.fetch({
                                reset: true
                            });
                        }
                    });
                }
            });
        },
        dropDownFixPosition: function (event) {
            var $btnGroup = $(event.target).closest('.btn-group');
            dropDownFixPosition($btnGroup.find('button'), $btnGroup.find('.dropdown-menu'));
        },
        addProduct: function (event) {
            var $originOption = $(event.target).closest('li'),
                model = this.collection.get($originOption.data('id'));
            if (!model) {
                return;
            }
            Cache.set('mpwsShopPopupProductInitOrigin', model.id + ';;' + model.get('Name'));
            Backbone.history.navigate(APP.instances.shop.urls.productCreate, true);
        },
        render: function () {
            var _data = Utils.getHBSTemplateData(this);
            _(_data.data).each(function (item) {
                item.contextButton = tplBtnMenuMainItem(Utils.getHBSTemplateData(item));
            });
            this.$el.html(tpl(_data));
            this.$('.dropdown-toggle').addClass('btn-link');
            this.restoreLayout();
            return this;
        }
    });

    function dropDownFixPosition(button, dropdown) {
        var dropDownTop = button.offset().top + button.outerHeight() - $('body').scrollTop();
        dropdown.css('top', dropDownTop + "px");
        dropdown.css('left', button.offset().left + "px");
    }


    return FilterPanelOrigins;

});