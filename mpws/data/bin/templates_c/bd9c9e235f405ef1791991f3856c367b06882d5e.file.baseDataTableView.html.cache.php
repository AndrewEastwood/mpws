<?php /* Smarty version Smarty-3.1.11, created on 2012-11-01 19:59:58
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/baseDataTableView.html" */ ?>
<?php /*%%SmartyHeaderCode:10408342675086fc118b2138-36715098%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd9c9e235f405ef1791991f3856c367b06882d5e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/baseDataTableView.html',
      1 => 1351792324,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10408342675086fc118b2138-36715098',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5086fc11913609_10596300',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    'CURRENT' => 0,
    '_widgetName' => 0,
    'DTV_CFG' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5086fc11913609_10596300')) {function content_5086fc11913609_10596300($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>



<?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("DataTableView", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? ((($tmp = @$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['RESOURCE'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['_widgetName']->value : $tmp)) : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)}, null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_title, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value), 0);?>



<?php if (!empty($_smarty_tpl->tpl_vars['DTV_CFG']->value['searchbox'])&&$_smarty_tpl->tpl_vars['DTV_CFG']->value['searchbox']['enabled']){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_searchBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_confing'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value,'_data'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SEARCH'],'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value), 0);?>

<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['DTV_CFG']->value['filtering'])&&$_smarty_tpl->tpl_vars['DTV_CFG']->value['filtering']['enabled']){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_quickFiltering, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_confing'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value), 0);?>

<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['DTV_CFG']->value['datatable'])){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataTable, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_confing'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value,'_data'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SOURCE']['RECORDS'],'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value), 0);?>

<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['DTV_CFG']->value['pagination'])&&$_smarty_tpl->tpl_vars['DTV_CFG']->value['pagination']['enabled']){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pagingBar, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_confing'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value,'_data'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['PAGING']), 0);?>

<?php }?>

</div><?php }} ?>