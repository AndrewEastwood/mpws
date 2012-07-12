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
        $('.MPWSControlOrderDateRange').datepicker({dateFormat: "yy-mm-dd 00:00:00"});
    });
    
    return { };
})(window, document, jQuery));