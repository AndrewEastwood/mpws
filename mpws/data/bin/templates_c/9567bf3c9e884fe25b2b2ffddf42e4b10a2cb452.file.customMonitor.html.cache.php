<?php /* Smarty version Smarty-3.1.11, created on 2012-11-12 19:28:10
         compiled from "/var/www/mpws/web/plugin/reporting/template/widget/customMonitor.html" */ ?>
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
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508fc2a853b740_02597490',
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
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508fc2a853b740_02597490')) {function content_508fc2a853b740_02597490($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("CustomMonitor", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable("customMonitor", null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidgetCustom MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">

    
    <div class="MPWSBlock MPWSBlockAllReportLinks">
        <?php  $_smarty_tpl->tpl_vars['entry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['entry']->_loop = false;
 $_smarty_tpl->tpl_vars['idx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['entry']->key => $_smarty_tpl->tpl_vars['entry']->value){
$_smarty_tpl->tpl_vars['entry']->_loop = true;
 $_smarty_tpl->tpl_vars['idx']->value = $_smarty_tpl->tpl_vars['entry']->key;
?>
            
        <ul class="MPWSList">
            <li class="MPWSListItem">
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>$_smarty_tpl->tpl_vars['entry']->value['ExternalKey'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_action'=>"ShowReport_".((string)$_smarty_tpl->tpl_vars['entry']->value['ID']),'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_href'=>"javascript://",'_single'=>true,'_useValueAsTitle'=>$_smarty_tpl->tpl_vars['entry']->value['Name'],'_mode'=>'system'), 0);?>

            
            <?php if (!empty($_smarty_tpl->tpl_vars['entry']->value['Reports'])){?>
                <ul class="MPWSList MPWSListSub">
                <?php $_smarty_tpl->tpl_vars['_scriptList'] = new Smarty_variable(explode(';',trim($_smarty_tpl->tpl_vars['entry']->value['Reports'],';')), null, 0);?>
                <?php  $_smarty_tpl->tpl_vars['scriptName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['scriptName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_scriptList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['scriptName']->key => $_smarty_tpl->tpl_vars['scriptName']->value){
$_smarty_tpl->tpl_vars['scriptName']->_loop = true;
?>
                    <li class="MPWSListItem">
                    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>$_smarty_tpl->tpl_vars['scriptName']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_action'=>"render",'_oid'=>$_smarty_tpl->tpl_vars['entry']->value['ID'],'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true,'_href'=>'javascript://','_useValueAsTitle'=>$_smarty_tpl->tpl_vars['scriptName']->value,'_customParams'=>array('script'=>$_smarty_tpl->tpl_vars['scriptName']->value),'_mpwsParams'=>array('caller'=>'reporting','realm'=>'plugin'),'_mode'=>'system'), 0);?>

                    </li>
                <?php } ?>
                </li>
            <?php }?>
            </li>
        </ul>
        <?php } ?>
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