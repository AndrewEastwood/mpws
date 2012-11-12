<?php /*%%SmartyHeaderCode:1855146331508fc2a84b44a9-03459475%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9567bf3c9e884fe25b2b2ffddf42e4b10a2cb452' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/customMonitor.html',
      1 => 1351881542,
      2 => 'file',
    ),
    'a291fbfdf58a8c4b968a9b89fa85536efefe03b7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/trigger/control.html',
      1 => 1352396826,
      2 => 'file',
    ),
    'd02973ef8e80aa05e187fe40f5356d8e16a2e28e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1351875980,
      2 => 'file',
    ),
    'f53137f2b7fe9fb4a8d2f0e039ef190f3c30373d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/link.html',
      1 => 1352443500,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1855146331508fc2a84b44a9-03459475',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50a0a508873265_70836398',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50a0a508873265_70836398')) {function content_50a0a508873265_70836398($_smarty_tpl) {?>
<div id="MPWSWidgetCustomMonitorID" class="MPWSWidget MPWSWidgetCustom MPWSWidgetCustomMonitor MPWSWidgetCustomMonitor">

    
    <div class="MPWSBlock MPWSBlockAllReportLinks">
                    
        <ul class="MPWSList">
            <li class="MPWSListItem">
            
    
<div class="MPWSBlock MPWSBlockControl MPWSBlockControl_mpwsLinkAction MPWSRenderModeNormal">


            
                                        


    

        



<a href="javascript://" title="SI Team"  class="MPWSLink" id="MPWSActionLink_ShowReport_15ID" mpws-fn="ShowReport_15">SI Team</a>

    

</div>
            
                            <ul class="MPWSList MPWSListSub">
                                                    <li class="MPWSListItem">
                    
    
<div class="MPWSBlock MPWSBlockControl MPWSBlockControl_mpwsLinkAction MPWSRenderModeNormal">


            
                                        


        
                            
                                



<a href="javascript://" title="weekly"  class="MPWSLink" id="MPWSActionLink_renderID" mpws-fn="render" mpws-oid="15" mpws-caller="reporting" mpws-realm="plugin" mpws-custom="script%3Dweekly">weekly</a>

    

</div>
                    </li>
                                    <li class="MPWSListItem">
                    
    
<div class="MPWSBlock MPWSBlockControl MPWSBlockControl_mpwsLinkAction MPWSRenderModeNormal">


            
                                        


        
                            
                                



<a href="javascript://" title="release"  class="MPWSLink" id="MPWSActionLink_renderID" mpws-fn="render" mpws-oid="15" mpws-caller="reporting" mpws-realm="plugin" mpws-custom="script%3Drelease">release</a>

    

</div>
                    </li>
                                </li>
                        </li>
        </ul>
            </div>

    
    <div id="MPWSBlockReportInjectionAreaUIID"></div>
    
    
    <script id="MPWSBlockReportInjectionAreaScriptID"></script>

    <script>
        $('.MPWSBlockAllReportLinks .MPWSListSub a').click(function() {
            
            var _mpws_caller = $(this).attr('mpws-caller');
            var _mpws_oid = $(this).attr('mpws-oid');
            var _mpws_realm = $(this).attr('mpws-realm');
            
            mpws.api.objectRequest(this, function(data) {
                //mpws.tools.log(data);
                // inject report
                mpws.module.get('visualReport').setup(
                    'MPWSBlockReportInjectionAreaUIID',
                    'MPWSBlockReportInjectionAreaScriptID',
                    data.UI,
                    data.SCRIPT,
                    callbackSetup
                );
                // do setup callback
                function callbackSetup(init) {
                    customReportScriptSetup(
                        init, 
                        {
                            caller: _mpws_caller,
                            fn: 'fetchdata',
                            oid: _mpws_oid,
                            realm: _mpws_realm
                        }
                    );
                }
            }, true);
        });
    </script>

</div><?php }} ?>