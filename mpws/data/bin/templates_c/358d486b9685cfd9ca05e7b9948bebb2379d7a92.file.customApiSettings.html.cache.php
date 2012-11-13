<?php /* Smarty version Smarty-3.1.11, created on 2012-11-13 16:40:04
         compiled from "/var/www/mpws/web/plugin/reporting/template/widget/customApiSettings.html" */ ?>
<?php /*%%SmartyHeaderCode:17510398675098d643eb5d76-86859458%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '358d486b9685cfd9ca05e7b9948bebb2379d7a92' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/customApiSettings.html',
      1 => 1352796441,
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
    'entry' => 0,
    '__apiReportParams' => 0,
    'scriptKey' => 0,
    '__apiScriptParams' => 0,
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
    
    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_reportingApiSettingsKey,'_value'=>$_smarty_tpl->tpl_vars['__key']->value), 0);?>

    
    <hr size="2">
    <hr size="2">
    
    <?php  $_smarty_tpl->tpl_vars['entry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['entry']->_loop = false;
 $_smarty_tpl->tpl_vars['idx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['entry']->key => $_smarty_tpl->tpl_vars['entry']->value){
$_smarty_tpl->tpl_vars['entry']->_loop = true;
 $_smarty_tpl->tpl_vars['idx']->value = $_smarty_tpl->tpl_vars['entry']->key;
?>
    
        
        <?php $_smarty_tpl->tpl_vars['__apiReportParams'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['__token']->value)."&realm=plugin&oid=".((string)$_smarty_tpl->tpl_vars['entry']->value['RECORD']['ID']), null, 0);?>
        <?php ob_start();?><?php echo urlencode($_smarty_tpl->tpl_vars['__apiReportParams']->value);?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_reportingApiSettingsImportLink,'_value'=>"http://".((string)$_SERVER['HTTP_HOST'])."/api.js?caller=reporting&fn=sf-import&p=".$_tmp1), 0);?>

        
            
            <?php if (!empty($_smarty_tpl->tpl_vars['entry']->value['SCRIPTS'])){?>
                <?php  $_smarty_tpl->tpl_vars['scriptName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['scriptName']->_loop = false;
 $_smarty_tpl->tpl_vars['scriptKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['entry']->value['SCRIPTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['scriptName']->key => $_smarty_tpl->tpl_vars['scriptName']->value){
$_smarty_tpl->tpl_vars['scriptName']->_loop = true;
 $_smarty_tpl->tpl_vars['scriptKey']->value = $_smarty_tpl->tpl_vars['scriptName']->key;
?>
                    <?php $_smarty_tpl->tpl_vars['__apiScriptParams'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['__token']->value)."&realm=plugin&oid=".((string)$_smarty_tpl->tpl_vars['entry']->value['RECORD']['ID'])."&custom=script%3D".((string)$_smarty_tpl->tpl_vars['scriptKey']->value), null, 0);?>
                    <?php ob_start();?><?php echo urlencode($_smarty_tpl->tpl_vars['__apiScriptParams']->value);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_reportingApiSettingsRenderLink,'_value'=>"http://".((string)$_SERVER['HTTP_HOST'])."/api.js?caller=reporting&fn=render&p=".$_tmp2), 0);?>

                <?php } ?>
            <?php }?>
        
        
        <hr size="2">
    
    <?php } ?>
    
    

</div><?php }} ?>