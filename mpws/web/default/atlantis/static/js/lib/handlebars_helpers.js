/* Simple native bridge to 3dp template engine
 * --------
 */
define("default/js/lib/handlebars_helpers", [
    'default/js/lib/underscore',
    'default/js/lib/extend.string'
], function (_) {
    // The module to be exported
    var helpers = {
        contains: function (str, pattern, options) {
            if (str.indexOf(pattern) !== -1) {
                return options.fn(this);
            }
            return options.inverse(this);
        },
        and: function (a, b, options) {
            if (a && b) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        gt: function (value, test, options) {
            if (value > test) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        gte: function (value, test, options) {
            if (value >= test) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        is: function (value, test, options) {
            if (value === test) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        isnt: function (value, test, options) {
            if (value !== test) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        lt: function (value, test, options) {
            if (value < test) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        lte: function (value, test, options) {
            if (value <= test) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        /**
         * Or
         * Conditionally render a block if one of the values is truthy.
         */
        or: function (a, b, options) {
            if (a || b) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        /**
         * {{#compare}}...{{/compare}}
         *
         * @credit: OOCSS
         * @param left value
         * @param operator The operator, must be between quotes ">", "=", "<=", etc...
         * @param right value
         * @param options option object sent by handlebars
         * @return {String} formatted html
         *
         * @example:
         *   {{#compare unicorns "<" ponies}}
         *     I knew it, unicorns are just low-quality ponies!
         *   {{/compare}}
         *
         *   {{#compare value ">=" 10}}
         *     The value is greater or equal than 10
         *     {{else}}
         *     The value is lower than 10
         *   {{/compare}}
         */
        compare: function (left, operator, right, options) {
            /*jshint eqeqeq: false*/
            if (arguments.length < 3) {
                throw new Error('Handlerbars Helper "compare" needs 2 parameters');
            }
            if (options === undefined) {
                options = right;
                right = operator;
                operator = '===';
            }
            var operators = {
                '==': function (l, r) {
                    return l == r;
                },
                '===': function (l, r) {
                    return l === r;
                },
                '!=': function (l, r) {
                    return l != r;
                },
                '!==': function (l, r) {
                    return l !== r;
                },
                '<': function (l, r) {
                    return l < r;
                },
                '>': function (l, r) {
                    return l > r;
                },
                '<=': function (l, r) {
                    return l <= r;
                },
                '>=': function (l, r) {
                    return l >= r;
                },
                'typeof': function (l, r) {
                    return typeof l == r;
                }
            };
            if (!operators[operator]) {
                throw new Error('Handlerbars Helper "compare" doesn\'t know the operator ' + operator);
            }
            var result = operators[operator](left, right);
            if (result) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        },
        /**
         * {{if_eq}}
         *
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{if_eq this compare=that}}
         */
        if_eq: function (left, right, options) {
            debugger;
            if (left === right) {
                return options.fn(this);
            }
            return options.inverse(this);
        },
        /**
         * {{unless_eq}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{unless_eq this compare=that}}
         */
        unless_eq: function (context, options) {
            if (context === options.hash.compare) {
                return options.inverse(this);
            }
            return options.fn(this);
        },
        /**
         * {{if_gt}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{if_gt this compare=that}}
         */
        if_gt: function (context, options) {
            if (context > options.hash.compare) {
                return options.fn(this);
            }
            return options.inverse(this);
        },
        /**
         * {{unless_gt}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{unless_gt this compare=that}}
         */
        unless_gt: function (context, options) {
            if (context > options.hash.compare) {
                return options.inverse(this);
            }
            return options.fn(this);
        },
        /**
         * {{if_lt}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{if_lt this compare=that}}
         */
        if_lt: function (context, options) {
            if (context < options.hash.compare) {
                return options.fn(this);
            }
            return options.inverse(this);
        },
        /**
         * {{unless_lt}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{unless_lt this compare=that}}
         */
        unless_lt: function (context, options) {
            if (context < options.hash.compare) {
                return options.inverse(this);
            }
            return options.fn(this);
        },
        /**
         * {{if_gteq}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{if_gteq this compare=that}}
         */
        if_gteq: function (context, options) {
            if (context >= options.hash.compare) {
                return options.fn(this);
            }
            return options.inverse(this);
        },
        /**
         * {{unless_gteq}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{unless_gteq this compare=that}}
         */
        unless_gteq: function (context, options) {
            if (context >= options.hash.compare) {
                return options.inverse(this);
            }
            return options.fn(this);
        },
        /**
         * {{if_lteq}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{if_lteq this compare=that}}
         */
        if_lteq: function (context, options) {
            if (context <= options.hash.compare) {
                return options.fn(this);
            }
            return options.inverse(this);
        },
        /**
         * {{unless_lteq}}
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{unless_lteq this compare=that}}
         */
        unless_lteq: function (context, options) {
            if (context <= options.hash.compare) {
                return options.inverse(this);
            }
            return options.fn(this);
        },
        /**
         * {{ifAny}}
         * Similar to {{#if}} block helper but accepts multiple arguments.
         * @author: Dan Harper <http://github.com/danharper>
         *
         * @param  {[type]} context [description]
         * @param  {[type]} options [description]
         * @return {[type]}             [description]
         *
         * @example: {{ifAny this compare=that}}
         */
        ifAny: function () {
            var argLength = arguments.length - 2;
            var content = arguments[argLength + 1];
            var success = true;
            var i = 0;
            while (i < argLength) {
                if (!arguments[i]) {
                    success = false;
                    break;
                }
                i += 1;
            }
            if (success) {
                return content(this);
            } else {
                return content.inverse(this);
            }
        }
    };
    // Aliases
    helpers.ifeq = helpers.if_eq;
    helpers.unlessEq = helpers.unless_eq;
    helpers.ifgt = helpers.if_gt;
    helpers.unlessGt = helpers.unless_gt;
    helpers.iflt = helpers.if_lt;
    helpers.unlessLt = helpers.unless_lt;
    helpers.ifgteq = helpers.if_gteq;
    helpers.unlessGtEq = helpers.unless_gteq;
    helpers.ifLtEq = helpers.if_lteq;
    helpers.unlessLtEq = helpers.unless_lteq;
    helpers.srtStartsWith = function (text, startPart, options) {
        if (text.startWith(startPart))
            return options.fn(this);
        return options.inverse(this);
    }
    // from: http://stackoverflow.com/a/12002281
    helpers.foreach = function (arr, options) {
        if (options.inverse && (!arr || !arr.length))
            return options.inverse(this);
        return arr.map(function (item, index) {
            item.$index = index;
            item.$first = index === 0;
            item.$last = index === arr.length - 1;
            return options.fn(item);
        }).join('');
    }
    // helpers.mpwsPartial = function (partialID, partialData) {
    // }
    helpers.mpwsIsEmpty = function (object, options) {
        return _.isEmpty(object) ? options.fn(this) : options.inverse(this);
    }
    helpers.mpwsIsNotEmpty = function (object, options) {
        return !_.isEmpty(object) ? options.fn(this) : options.inverse(this);
    }
    helpers.in_array = function (array, value, options) {
        return array && _(array).indexOf(value) > -1 ? options.fn(this) : options.inverse(this);
    }
    helpers.has = function (object, key, options) {
        // debugger;
        return _(object).has(key) ? options.fn(this) : options.inverse(this);
    }
    helpers.isNull = function (value, options) {
        return value === null ? options.fn(this) : options.inverse(this);
    }
    // helpers.mpwsPartial = function (templateName, partialData) {
    //     var _partial = Handlebars.partials[templateName];
    //     return new Handlebars.SafeString(Handlebars.compile(_partial)(partialData || this));
    // }
    helpers.mpwsToInt = function (value, context) {
        return parseInt(value, 10);
    }
    helpers.debug = function (optionalValue) {
        console.log("Current Context");
        console.log("====================");
        console.log(this);
        if (optionalValue) {
            console.log("Value");
            console.log("====================");
            console.log(optionalValue);
        }
    }
    helpers.toLowerCase = function(str) {
      return str.toLowerCase();
    }
    helpers.mpwsGetValueByKey = function(dictionary, key, prefix, suffix, getFirstPartSplittedBy, context) {
        // debugger;
        if (!context && suffix && suffix.hash) {
            context = {
                hash: _(suffix.hash).clone()
            }
            suffix = "";
        }
        var _key = prefix + key + suffix;
        return dictionary[_key];
    }
    // Warning: untested code
    helpers.each_upto = function(ary, max, options) {
        if(!ary || ary.length == 0)
            return options.inverse(this);

        var result = [ ];
        for(var i = 0; i < max && i < ary.length; ++i)
            result.push(options.fn(ary[i]));
        return result.join('');
    }
    var audaciousFn;
    helpers.recursive = function(children, options) {
        var out = '';
        if (typeof options.fn !== "undefined") {
            audaciousFn = options.fn;
        }
        _(children).each(function(child){
            out = out + audaciousFn(child);
        });
        return out;
    }
    helpers.currency = function(amount, options) {
        if (typeof(amount) === 'string') { amount = options.contexts[0].get(amount); }
        var rounded = Math.round(amount * 100);
        var dec = rounded % 100;
        var whole = Math.round(rounded / 100 - dec / 100);
        var decStr = '' + dec;
        return /*'$' + */whole + '.' + decStr + ( decStr.length < 2 ? '0' : '');
    }
    return helpers;

});