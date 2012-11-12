<?php /*%%SmartyHeaderCode:1855146331508fc2a84b44a9-03459475%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9567bf3c9e884fe25b2b2ffddf42e4b10a2cb452' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/customMonitor.html',
      1 => 1352741283,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1855146331508fc2a84b44a9-03459475',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50a131aaa7a039_37401183',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_widgetName' => 0,
    'CURRENT' => 0,
    'entry' => 0,
    '_scriptList' => 0,
    'scriptName' => 0,
  ),
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50a131aaa7a039_37401183')) {function content_50a131aaa7a039_37401183($_smarty_tpl) {?>
<div id="MPWSWidgetCustomMonitorID" class="MPWSWidget MPWSWidgetCustom MPWSWidgetCustomMonitor MPWSWidgetCustomMonitor">

    
    <div class="MPWSBlock MPWSBlockAllReportLinks">
        
Notice: Undefined index: RECORD in /mydata/GitHub/web/mpws/data/bin/templates_c/9567bf3c9e884fe25b2b2ffddf42e4b10a2cb452.file.customMonitor.html.cache.php on line 44
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