APP.Modules.register("view/BRowViewClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'view/BCellViewClass',
    'lib/htmlComponents'

], function (app, Sandbox, $, _, Backbone, BCellViewClass, HtmlComponents) {

    var _libHtml = new HtmlComponents();

    var BRowViewClass = Backbone.View.extend({
        tagName : 'div',
        className : 'data-table-row',
        events: {
            "all": 'eventToTableDispatcher',
            "mouseenter": "eventToTableDispatcher",
            "mouseleave": "eventToTableDispatcher",
        },
        initialize: function () {
            this.model.on('change:uiState', this.uiStateChanged, this);
        },
        uiStateChanged: function (event) {
            if (this.model.getTable().isEdit()) {
                this.$el.removeClass('render-mode-selected');
                if (this.model.isEdit())
                    this.$el.addClass('render-mode-edit');
            } else {
                // add render-mode-selected to data-row element and related attribute rows
                _(this.$el).each(function(el){
                    $(el).removeClass('render-mode-edit');
                    if (event.type === 'mouseenter')
                        $(el).addClass('render-mode-selected');
                    if (event.type === 'mouseleave')
                        $(el).removeClass('render-mode-selected');
                });
            }
        },
        eventToTableDispatcher: function (event) {
            // app.log(true, 'BRowViewClass >>> eventToTableDispatcher', event, arguments, this);
            var state = 'row';
            var self = this;
            if (this.model.get('isHead'))
                state += 'Header';
            else
                state += 'Data';
            state += (event && event.type.capitalize());
            // app.log(true, 'view/BRowViewClass eventToTableDispatcher ', state);
            this.model.getTable().trigger("table:state", state, {
                event: event,
                viewRow: self,
                modelRow: self.model
            });

            // add essential class names to this (data-row) element
            // according to model uiState property
            this.uiStateChanged(event);
        },
        render : function (/*event*/) {
            // app.log(true, 'BRowViewClass >> render ', this);
            var _config = this.model.getConfig();

            function _renderRow (rowEl, cellModels, modelRow) {
                var viewCells = [];

                // walk through all cell models and render them
                cellModels.each(function(modelCell){

                    // modelCell.setState(modelRow.getState());
                    // app.log(true, 'BRowViewClass >> render >> modelCell', modelCell, 'modelRow', modelRow);
                    var view = new BCellViewClass({
                        model : modelCell
                    });

                    if (view.render())
                        viewCells.push(view.$el);

                });

                if (modelRow.get('isHead'))
                    rowEl.addClass('data-table-row-header');
                else
                    rowEl.addClass('data-table-row-data');

                if (modelRow.get('isAttributeRow'))
                    rowEl.addClass('data-table-row-attribute');

                if (modelRow.isEdit())
                    rowEl.addClass('render-mode-edit');

                if (_config.style.setRowCustomStateByKey) {
                    var cellEntry = cellModels.where({'accessKey': _config.style.setRowCustomStateByKey});
                    if (cellEntry && cellEntry.length > 0)
                        cellEntry = cellEntry[0];
                    if (cellEntry)
                        rowEl.addClass('data-table-row-state-' + _config.style.setRowCustomStateByKey + '_' + cellEntry.getValue());
                }

                rowEl.html(viewCells);

                return rowEl;
            }

            var self = this;
            
            // render data row
            this.$el = _renderRow(this.$el.clone(true), this.model.get('cellCollection'), this.model);

            // render attribute rows
            if (this.model.get('linkedAttributeRowModels').length) {
                // render data row with linked attribute rows
                var rows = [this.$el.clone(true)];
                _(this.model.get('linkedAttributeRowModels')).each(function(attrRowModel){
                    rows.push(_renderRow(self.$el.clone(true), attrRowModel.get('cellCollection'), attrRowModel));
                });
                this.$el = rows;
            }

            return this;
        },
    });

    return BRowViewClass;

})