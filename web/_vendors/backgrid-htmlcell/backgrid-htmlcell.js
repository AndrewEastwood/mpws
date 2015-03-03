define([
    'jquery',
    'underscore',
    'backgrid'
], function ($, _, Backgrid) {

    'use strict';

    var HtmlCell = Backgrid.HtmlCell = Backgrid.Cell.extend({

      /** @property */
      className: 'html-cell',

      render: function () {
        this.$el.empty();
        var model = this.model;
        this.$el.html(this.formatter.fromRaw(model.get(this.column.get('name')), model));
        this.delegateEvents();
        return this;
      }

    });

    return Backgrid;
});
