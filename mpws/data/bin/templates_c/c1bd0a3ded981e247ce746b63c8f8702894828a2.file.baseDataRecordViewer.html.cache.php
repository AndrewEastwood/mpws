<?php /* Smarty version Smarty-3.1.11, created on 2012-11-06 12:45:46
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/baseDataRecordViewer.html" */ ?>
<?php /*%%SmartyHeaderCode:2033588813508fbdf74d3260-83692079%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c1bd0a3ded981e247ce746b63c8f8702894828a2' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/baseDataRecordViewer.html',
      1 => 1352198308,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2033588813508fbdf74d3260-83692079',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508fbdf77e5468_34602908',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    'CURRENT' => 0,
    '_widgetName' => 0,
    'DTV_CFG' => 0,
    'fieldName' => 0,
    'fieldValue' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508fbdf77e5468_34602908')) {function content_508fbdf77e5468_34602908($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("DataRecordViewer", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? ((($tmp = @$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['RESOURCE'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['_widgetName']->value : $tmp)) : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_dataRecordViewer".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'])}, null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD'][$_smarty_tpl->tpl_vars['DTV_CFG']->value['caption']]), 0);?>

    <?php  $_smarty_tpl->tpl_vars['fieldValue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldValue']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldValue']->key => $_smarty_tpl->tpl_vars['fieldValue']->value){
$_smarty_tpl->tpl_vars['fieldValue']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldName']->value = $_smarty_tpl->tpl_vars['fieldValue']->key;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLabelValue','_name'=>$_smarty_tpl->tpl_vars['fieldName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_value'=>$_smarty_tpl->tpl_vars['fieldValue']->value), 0);?>

    <?php } ?>

    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>'BackToRecords','_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_action'=>'BackToRecords','_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['REFERER'],'_single'=>true,'_mode'=>'system'), 0);?>

    </div>
</div><?php }} ?>