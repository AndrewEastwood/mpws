<?php /* Smarty version Smarty-3.1.11, created on 2012-10-31 09:54:43
         compiled from "/var/www/mpws/web/plugin/reporting/template/widget/customReportScriptEditor.html" */ ?>
<?php /*%%SmartyHeaderCode:2063721843508ffead476a31-59237467%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47a03a36bc692a03c60e9b27c3c4c3ec02016c6c' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/customReportScriptEditor.html',
      1 => 1351670075,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2063721843508ffead476a31-59237467',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508ffead50a799_41256091',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    'CURRENT' => 0,
    '_widgetName' => 0,
    'entry' => 0,
    '_scriptList' => 0,
    'scriptName' => 0,
    'DTV_CFG' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508ffead50a799_41256091')) {function content_508ffead50a799_41256091($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("CustomReportScriptEditor", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable("customReportScriptEditor", null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)}, null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidgetCustom MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    
    
    <div class="MPWSBlock MPWSBlockAllReportLinks">
        <?php  $_smarty_tpl->tpl_vars['entry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['entry']->_loop = false;
 $_smarty_tpl->tpl_vars['idx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['REPORTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
                    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>$_smarty_tpl->tpl_vars['scriptName']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_action'=>"EditReport",'_oid'=>$_smarty_tpl->tpl_vars['entry']->value['ID'],'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true,'_useValueAsTitle'=>$_smarty_tpl->tpl_vars['scriptName']->value,'_customParams'=>array('script'=>$_smarty_tpl->tpl_vars['scriptName']->value),'_mode'=>'system'), 0);?>

                    </li>
                <?php } ?>
                </li>
            <?php }?>
            </li>
        </ul>
        <?php } ?>
    </div>
    
    
    
    <div class="MPWSBlock MPWSBockReportScriptEditArea">
        <?php if ($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ACTION']=='editreport'){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsRTEWH, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['editor']['type'],'_name'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['editor']['name'],'_value'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SCRIPT'],'_useDivWrapper'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['editor']['divmode'],'_jslib'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['editor']['jslib']), 0);?>

        <?php }?>
    </div>
    
</div><?php }} ?>