qB.Modules.register("view/BCellViewClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'lib/htmlComponents'

], function(qB, Sandbox, $, _, Backbone, HtmlComponents) {

    var _libHtml = new HtmlComponents();

    var BCellViewClass = Backbone.View.extend({
        tagName : 'div',
        events: {
            'click': 'eventToTableDispatcher'
        },
        initialize: function () {
            this.model.on('change:uiState', this.uiStateChanged, this);
            this.model.on('refresh', this.render, this);
        },
        eventToTableDispatcher: function (event) {
            // qB.log(true, 'BCellViewClass >> eventToTableDispatcher', event, arguments);
            var state = 'cell';
            var self = this;
            if (this.model.get('isHeader'))
                state += 'Header';
            else
                state += 'Data';
            state += (event && event.type.capitalize());
            this.model.getTable().trigger("table:state", state, {
                event: event,
                viewCell: self,
                modelCell: self.model
            });
        },
        uiStateChanged: function () {
            // qB.log(true, 'BCellViewClass >> uiStateChanged re-rendering beacuse state is changed');
            // if (this.model.isEdit()) {
                this.render();
            // }
        },
        template: function(attr) {
            // qB.log(true, 'BCellViewClass >> template', attr);

            if (!attr.isVisible)
                return false;
            
            var _config = this.model.getConfig();

            // skip unregistered cell
            if (!_(_config.columns).has(attr.accessKey))
                return false;

            var classNameCell = ["data-table-cell data-table-cell-" + attr.accessKey];
            var classWrapperCell = ["data-table-cell-content-wrapper"];
            var cellContent = _libHtml.generateTag({
                tag: 'span',
                attr : {
                    class : "data-table-cell-content " + attr.accessKey
                }
            });

            if (attr.isEditable)
                classNameCell.push("data-table-cell-editable");

            if (attr.isSortable)
                classNameCell.push("data-table-cell-sortable");

            if (attr.type)
                classNameCell.push("data-table-cell-type_" + attr.type);

            if (attr.overflowEllipsis) {
                classNameCell.push('render-oneline-wrapper');
                classWrapperCell.push('render-oneline-wrapper');
            }

            if (attr.isAttribute)
                classNameCell.push('data-table-cell-attribute');


            // format cell content
            var cellValue = attr.isHeader ? attr.caption || "" : attr.value;

            if (attr.isHeader && _.isFunction(attr.formatCaption))
                cellValue = attr.formatCaption(cellValue, this, this.model, cellContent);

            if (!attr.isHeader && _.isFunction(attr.formatValue))
                cellValue = attr.formatValue(cellValue, this, this.model, cellContent);


            if (_.isUndefined(cellValue) || cellValue.toString().length === 0) {
                // qB.log(true, 'cell is empty, adding empty space')
                cellContent.html(_libHtml.getEmptySpace());
            } else
                cellContent.append(cellValue);

            // format cell title
            var cellTitle = (typeof attr.title === "string") ? attr.title : $(cellContent).text();

            if (attr.isHeader && _.isFunction(attr.formatCaptionTitle))
                cellTitle = attr.formatCaptionTitle(cellTitle, this, this.model);

            if (!attr.isHeader && _.isFunction(attr.formatValueTitle))
                cellTitle = attr.formatValueTitle(cellTitle, this, this.model);

            var _cellWrapper = _libHtml.generateTag({
                tag: 'span',
                attr: {
                    class: classWrapperCell.join(" ")
                },
                html: cellContent
            });

            if (attr.isHeader && attr.isSortable && attr.state === 'sorted') {
                if (attr.sortMode === 'sort-asc')
                    _cellWrapper.append(_libHtml.getElement('arrow-dn'));
                if (attr.sortMode === 'sort-desc')
                    _cellWrapper.append(_libHtml.getElement('arrow-up'));
            }

            var _originalClassNames = this.$el.attr('class');

            this.$el.attr({
                class : classNameCell.join(" "),
                title: cellTitle.trim()
            });

            if (_originalClassNames)
                this.$el.addClass(_originalClassNames);

            if (_.isString(attr.width))
                this.$el.css('width', attr.width);

            return _cellWrapper;

        },
        render: function() {
            // qB.log(true, 'BCellViewClass >> render');
            if (!this.model.get('isInAttributeRow') && this.model.get('isAttribute') && !this.model.get('isHeader'))
                return false;

            var cellContent = this.template(this.model.attributes);
            if (!cellContent)
                 return false;
            
            this.$el.html(cellContent);
            return this;
        },
        customAddClass: function (className) {
            this.$el.addClass(_libHtml.getCssScopeArray("data-table-cell", className).pop());
        }
    });

    return BCellViewClass;

})