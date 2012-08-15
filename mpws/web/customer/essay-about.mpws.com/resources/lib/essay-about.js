//alert('customer');
mpws.module.define('essay-about', (function(window, document, $){
    
    $(document).ready(function () {
        // Set a callback to run when the Google Visualization API is loaded.
        //google.setOnLoadCallback(drawChart);
        // chnages message status
        $('.MPWSControl_MessageMarkAsRead').click(function(){
            mpws.api.objectRequest(this);
        });
        // date pickers
        $('.MPWSControlOrderDateRange').datepicker({
                showOn: "button",
                buttonImage: "/static/icons/calendar.png",
                dateFormat: "yy-mm-dd 00:00:00",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true
        });
        
        // resources
        $('#MPWSOrderSourceCountID').change(function(){
            // get current sources count
            var _currentSrcs = $('#MPWSSectionOrderSourcesLinksID input[type="text"]');
            var _newCount = $(this).val();
            var _field = '<div class="MPWSSourceRow"><input type="text" name="order_source_links[]" value=""/></div>';
            var _cDiff = _newCount - _currentSrcs.length;
            
            //console.log(_newCount);
            if (_currentSrcs.length < _newCount) {
                var _newFields = '';
                for(var i = 0; i < _cDiff ; i++)
                    _newFields += _field;
                $('#MPWSSectionOrderSourcesLinksID').append(_newFields);
            } else {
                if (_newCount == 0)
                    $('#MPWSSectionOrderSourcesLinksID').html('<div class="MPWSSourceRow"></div>');
                else
                    $($('#MPWSSectionOrderSourcesLinksID .MPWSSourceRow:gt('+ _newCount +')')).remove();
            }
            
        });
        
        // add +1 order source
        $('#MPWSOrderAddMoreSourcesID').click(function(){
            var _field = '<div class="MPWSSourceRow"><input type="text" name="order_source_links[]" value=""/><a href="#_remove" id="MPWSOrderRemoveSourcesID">remove</a></div>';
            $('#MPWSSectionOrderSourcesLinksID').append(_field);
        });
        // remove order source
        $('#MPWSOrderRemoveSourcesID').live('click', function(){
            $(this).parent().remove();
        });
        
        // make order datetime picker
        $('#MPWSControlMakeOrderDateDeadlineID').datetimepicker({
                showOn: "button",
                buttonImage: "/static/icons/calendar.png",
                dateFormat: "yy-mm-dd",
                timeFormat: "hh:00:00",
                changeMonth: true,
                changeYear: true,
                showMinute: false,
                showButtonPanel: true//,
                //minDate: new Date(new Date().getTime() + (3*60*60*1000))
        });
        
        // login availability
        $('#MPWSButtonCheckLoginID').click(function(){
            var requester = mpws.api.getObjectJSON(this);
            requester.value = $('#MPWSControlTextUserLoginRegisterID').val();
            //mpws.tools.log(requester);
            //mpws.tools.log(requester.getUrl());
            mpws.api.send(requester.getUrl(), false, mpwsLoginStateReceived)
            // MPWSControlTextUserLoginRegisterID
        });

        // upload banner
        if (mpws.page === "make-order") {
            var _tooltip = new mpws.ui.tooltip();
            _tooltip.setup({
                binder: 'MPWSFormHintSourceBannerID',
                prefix: 'SourceTipBanner',
                animateIn: 1,
                animateOut: 1
            });
            $('a.MPWSSourceTipBanner').hover(
                function(){
                    _tooltip.setup({
                        prefix: 'SourceTipBanner_' + mpws.tools.parseUrl($(this).attr('href')).host
                    }).showModal();
                },
                function(){
                    _tooltip.clearAll();
                }
            );
        }

        
    });
    
    function mpwsLoginStateReceived (data) {
        var _tooltip = new mpws.ui.tooltip();
        _tooltip.setup({
            binder: 'MPWSControlTextUserLoginRegisterID',
            prefix: 'UserDoubleLogin',
            removeAfter: 5000
        });
        
        
        //mpws.tools.log(_tooltip.getSettings());
        
        if (data && data.login == '')
            _tooltip.setup({text:'Login is empty.'}).showModal();
        else {
            if (data && !data.isAvailable)
                _tooltip.setup({text:'Login is already used.'}).showModal();
            else
                _tooltip.setup({text:'Available.'}).showModal();
        }
        //mpws.tools.log(data);
    }
    
    return { };
})(window, document, jQuery));
