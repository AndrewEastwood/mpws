(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("default/js/lib/bootstrapvalidator/validator/notEmpty", ['default/js/lib/bootstrapvalidator/bootstrapValidator', 'jquery'], factory);
    }
    else {
        factory($.fn.bootstrapValidator, jQuery);
    }
}(function (bootstrapValidator, $) {
    bootstrapValidator.i18n.notEmpty = $.extend(bootstrapValidator.i18n.notEmpty || {}, {
        'default': 'Please enter a value'
    });

    bootstrapValidator.validators.notEmpty = {
        enableByHtml5: function($field) {
            var required = $field.attr('required') + '';
            return ('required' === required || 'true' === required);
        },

        /**
         * Check if input value is empty or not
         *
         * @param {BootstrapValidator} validator The validator plugin instance
         * @param {jQuery} $field Field element
         * @param {Object} options
         * @returns {Boolean}
         */
        validate: function(validator, $field, options) {
            var type = $field.attr('type');
            if ('radio' === type || 'checkbox' === type) {
                return validator
                            .getFieldElements($field.attr('data-bv-field'))
                            .filter(':checked')
                            .length > 0;
            }

            return $.trim($field.val()) !== '';
        }
    };
}));