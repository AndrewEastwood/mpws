<?php /* Smarty version Smarty-3.1.11, created on 2012-11-06 11:38:06
         compiled from "/var/www/mpws/web/plugin/reporting/template/widget/customApiSettings.html" */ ?>
<?php /*%%SmartyHeaderCode:17510398675098d643eb5d76-86859458%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '358d486b9685cfd9ca05e7b9948bebb2379d7a92' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/customApiSettings.html',
      1 => 1352194658,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17510398675098d643eb5d76-86859458',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5098d643ee4363_21686240',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_widgetName' => 0,
    'CURRENT' => 0,
    '__key' => 0,
    '__token' => 0,
    '__apiParams' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5098d643ee4363_21686240')) {function content_5098d643ee4363_21686240($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("CustomApiSettings", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable("customApiSettings", null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidgetCustom MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    
    <?php $_smarty_tpl->tpl_vars['__key'] = new Smarty_variable(md5($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_masterJsApiKey), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['__token'] = new Smarty_variable("token=".((string)$_smarty_tpl->tpl_vars['__key']->value), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['__apiParams'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['__token']->value)."&realm=plugin", null, 0);?>
    
    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_customApiSettingsSForceImportAction,'_value'=>$_smarty_tpl->tpl_vars['__key']->value), 0);?>

    
    <?php ob_start();?><?php echo urlencode($_smarty_tpl->tpl_vars['__apiParams']->value);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_customApiSettingsSForceImportAction,'_value'=>"http://".((string)$_SERVER['HTTP_HOST'])."/api.js?caller=reporting&fn=sf-import&p=".$_tmp1), 0);?>


</div><?php }} ?>