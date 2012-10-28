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
        
        // process custom scripts
        mpws.loader.processAll();
    });
})();

