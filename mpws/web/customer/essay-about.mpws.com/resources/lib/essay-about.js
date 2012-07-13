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
                dateFormat: "yy-mm-dd 00:00:00",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true
        });
        
        $('#MPWSOrderSourceCountID').change(function(){
            // get current sources count
            var _currentSrcs = $('#MPWSSectionOrderSourcesLinksID input[type="text"]');
            var _newCount = $(this).val();
            var _field = '<div class="MPWSSourceRow"><input type="text" name="order_source_links[]" value=""/></div>';
            var _cDiff = _newCount - _currentSrcs.length;
            
                console.log(_newCount);
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
    });
    
    return { };
})(window, document, jQuery));