(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("default/js/lib/bootstrapvalidator/validator/regexp", ['default/js/lib/bootstrapvalidator/bootstrapValidator', 'cmn_jquery'], factory);
    }
    else {
        factory($.fn.bootstrapValidator, jQuery);
    }
}(function (bootstrapValidator, $) {
    bootstrapValidator.i18n.regexp = $.extend(bootstrapValidator.i18n.regexp || {}, {
        'default': 'Please enter a value matching the pattern'
    });

    bootstrapValidator.validators.regexp = {
        html5Attributes: {
            message: 'message',
            regexp: 'regexp'
        },

        enableByHtml5: function($field) {
            var pattern = $field.attr('pattern');
            if (pattern) {
                return {
                    regexp: pattern
                };
            }

            return false;
        },

        /**
         * Check if the element value matches given regular expression
         *
         * @param {BootstrapValidator} validator The validator plugin instance
         * @param {jQuery} $field Field element
         * @param {Object} options Consists of the following key:
         * - regexp: The regular expression you need to check
         * @returns {Boolean}
         */
        validate: function(validator, $field, options) {
            var value = $field.val();
            if (value === '') {
                return true;
            }

            var regexp = ('string' === typeof options.regexp) ? new RegExp(options.regexp) : options.regexp;
            return regexp.test(value);
        }
    };
}));