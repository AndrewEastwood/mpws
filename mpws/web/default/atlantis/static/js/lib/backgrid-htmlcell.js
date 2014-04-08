define("default/js/lib/backgrid-htmlcell", [
    "cmn_jquery",
    "default/js/lib/underscore",
    "default/js/lib/backbone",
    "default/js/lib/backgrid"
], function ($, _, Backbone, Backgrid) {

    "use strict";

    var HtmlCell = Backgrid.HtmlCell = Backgrid.Cell.extend({
    // var HtmlCell = Backgrid.HtmlCell = Cell.extend({

      /** @property */
      className: "html-cell",

      render: function () {
        this.$el.empty();
        var model = this.model;
        this.$el.html(this.formatter.fromRaw(model.get(this.column.get("name")), model));
        this.delegateEvents();
        return this;
      }

    });

    return Backgrid;
});