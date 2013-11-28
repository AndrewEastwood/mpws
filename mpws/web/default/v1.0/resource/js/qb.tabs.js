// qBeats tabs libarary
// -----------------------------------
//
qB.Modules.register("lib/qb.tabs", [], [
    /* import dep packages */
    "lib/jquery",
    'lib/htmlComponents',
], function(qB, Sandbox, $, HtmlComponents){

    var _libHtml = new HtmlComponents();

    function qbTabs (com) {
        var _tabCom = $(com);
        var _tabButtonsCnt = _tabCom.find('.component-tab-buttons');
        var _tabPagesCnt = _tabCom.find('.component-tab-pages');
        var _tabButtons = _tabButtonsCnt.find('.component-tab-button');
        var _tabPages = _tabPagesCnt.find('.component-tab-page');
        
        var _tabButtonNamePrefix = 'component-tab-button_';
        var _tabPageNamePrefix = 'component-tab-page_';

        var _hideAll = function () {
            $(_tabButtons).each(function() {
                // hide all tab buttons
                // if (!$(this).hasClass(HtmlComponents.CSS_RENDER_HIDDEN))
                $(this).removeClass(HtmlComponents.CSS_RENDER_ACTIVE);
            });
            $(_tabPages).each(function() {
                // hide all tab pages
                if (!$(this).hasClass(HtmlComponents.CSS_RENDER_HIDDEN))
                    $(this).addClass(HtmlComponents.CSS_RENDER_HIDDEN);
            });
        }

        var _setActiveTab = function (tabPageName) {

            _hideAll();

            // set active tap button
            $(_tabButtonsCnt).find((_tabButtonNamePrefix + tabPageName).asCssClass()).addClass(HtmlComponents.CSS_RENDER_ACTIVE);

            // display related tab page
            $(_tabPagesCnt).find((_tabPageNamePrefix + tabPageName).asCssClass()).removeClass(HtmlComponents.CSS_RENDER_HIDDEN);
        }

        // qB.log(_tabCom);
        var _getTabPageName = function (tabButtonElement) {
            return _libHtml.getCssNames($(tabButtonElement))[1].replace(_tabButtonNamePrefix, '');
        }

        var _isActive = function (tabPageName) {
            return $(_tabButtonsCnt).find((_tabButtonNamePrefix + tabPageName).asCssClass()).hasClass(HtmlComponents.CSS_RENDER_ACTIVE) &&
                !$(_tabPagesCnt).find((_tabPageNamePrefix + tabPageName).asCssClass()).hasClass(HtmlComponents.CSS_RENDER_HIDDEN);
        }

        $(_tabButtons).on('click', function () {
            var _destTabPage = _getTabPageName($(this));
            if (!_isActive(_destTabPage))
                _setActiveTab(_destTabPage);
        })

        // get active tab page or set firts page as active
        var _activeButton = _tabButtonsCnt.find(HtmlComponents.CSS_RENDER_ACTIVE.asCssClass());
        if (_activeButton.length === 0)
            _activeButton = _tabButtonsCnt.children().first();

        _hideAll();

        // activate tab button
        _setActiveTab(_getTabPageName(_activeButton));

        // show tab component
        _tabCom.removeClass(HtmlComponents.CSS_RENDER_HIDDEN);
    }

    $.fn.qbTabs = function () {
        return this.each(function(){
            qbTabs($(this))
        });
    }

});