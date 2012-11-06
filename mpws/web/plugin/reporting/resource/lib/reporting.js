mpws.module.define('reporting', (function(window, document, $){
         
    // configure visual report
    function _initUserPaging (owner) {
        var _pageId = '#userPageID[dashboard="' + owner + '"]';
        // init pager
        mpws.tools.log('init paging for: ' + _pageId);
        $(_pageId + ' .pageNavigator a').click(function() {
            var _ref = $(this).attr('reference');
            mpws.tools.log('showing ' + _ref);

            //
            $(_pageId + ' .pageNavigator a.activePage').removeClass('activePage');
            $(this).addClass('activePage');

            //
            $(_pageId + ' .active').addClass('normal').removeClass('active');
            $(_pageId + ' #' + _ref).addClass('active').removeClass('normal');

            // sliding
            var _ml = ($(this).html() - 1) * -870;
            mpws.tools.log('sliding ' + _ml);
            $(_pageId + ' .reportWrapper').animate({marginLeft: _ml + 'px'});
        });
    };
    
    return {
        initUserPaging: _initUserPaging
    };
         
    
})(window, document, jQuery));
