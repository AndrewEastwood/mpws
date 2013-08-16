/* 
 * MPWS Post Script Actions
 */

(function(){
    $(document).ready(function(){
        // adding broser agent info
        var _agentName = navigator.appName.replace(new RegExp( " ", "g" ), '');
        mpws.tools.log('adding browser info: ' + 'MPWSBrowser' + _agentName);
        $('body').addClass('MPWSBrowser' + _agentName);
        // configure require js
        

        $('input:checkbox').on('change', function(){
        	$(this).val($(this).is(':checked') ? 1 : 0);
        });
        $('input:checkbox').each(function(){
        	$(this).val($(this).is(':checked') ? 1 : 0);
        });

        // process custom scripts
        mpws.loader.processAll();
    });
})();

