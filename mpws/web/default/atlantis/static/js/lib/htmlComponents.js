/*
 * Library: Html Components Generator
*/

define("default/js/lib/htmlComponents", [
    'cmn_jquery',
    'default/js/lib/underscore'
    /* component implementation */
], function ($, _) {

    function HtmlComponents () {}

    /* consts */
    HtmlComponents.CSS_RENDER_READONLY = 'render-readonly';
    HtmlComponents.CSS_RENDER_HIDDEN = 'render-hidden';
    HtmlComponents.CSS_RENDER_GOST = 'render-gost';
    HtmlComponents.CSS_RENDER_DISABLED = 'render-disabled';
    HtmlComponents.CSS_RENDER_LOADING = 'render-loading';
    HtmlComponents.CSS_RENDER_LABEL = 'render-label';
    HtmlComponents.CSS_RENDER_VALUE = 'render-value';
    HtmlComponents.CSS_RENDER_CONTROL = 'render-control';
    HtmlComponents.CSS_RENDER_ERRORS = 'render-errors';
    HtmlComponents.CSS_RENDER_EDIT = 'render-edit';
    HtmlComponents.CSS_RENDER_ACTIVE = 'render-active';
    HtmlComponents.CSS_ELEM_ICON = 'icon';

    /* attributes */
    HtmlComponents.getUniqueCssAttr = function (baseClass) {
        var _uid = (new Date()).getTime();
        
        var _cssClassNames = [baseClass, baseClass + "_" + _uid];
        
        return {
            cssClassList: _cssClassNames,
            cssClassStr: _cssClassNames.join(' '),
            cssID: _cssClassNames.pop() + 'ID'
        }
    }

    HtmlComponents.getUniqueCssClass = function (baseClass) {
        return HtmlComponents.getUniqueCssAttr(baseClass).cssClassStr;
    }

    HtmlComponents.getUniqueCssID = function (baseClass) {
        return HtmlComponents.getUniqueCssAttr(baseClass).cssID;
    }


    HtmlComponents.getCssDefaultName = function (name) {
        return name || 'default';
    }


    HtmlComponents.getCssNames = function (element) {
        var className = element && $(element).attr('class');
        if (className)
            return className.split(" ");
        return [];
    }

    HtmlComponents.getParentElement = function (element) {
        return $(element).parent();
    }

    HtmlComponents.getCssScopeArray = function (baseCssName, names) {
        return HtmlComponents.setCssScope(baseCssName, names).split(' ');
    }

    HtmlComponents.setCssScope = function (baseCssName, names) {

        if (_.isString(names))
            names = HtmlComponents.getCssDefaultName(names).split(" ");
        
        if (!_.isArray(names))
            names = [];

        if (baseCssName)
            return _(names).reduce(function(memo, name) {
                return memo + " " + baseCssName + '-' + name;
            }, baseCssName).toLowerCase();
    }

    HtmlComponents.elementToggleDisable = function (el, disabled) {
        if (disabled) {
            $(el).addClass(HtmlComponents.CSS_RENDER_DISABLED).attr('disabled', true);
        }
        else
            $(el).removeClass(HtmlComponents.CSS_RENDER_DISABLED).attr('disabled', null);
        return $(el);
    }


    return HtmlComponents;

});