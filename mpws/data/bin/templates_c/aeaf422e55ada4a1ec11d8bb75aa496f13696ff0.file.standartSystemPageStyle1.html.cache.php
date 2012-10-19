<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:34:09
         compiled from "/var/www/mpws/web/default/v1.0/template/page/standartSystemPageStyle1.html" */ ?>
<?php /*%%SmartyHeaderCode:447515331508156d1c49728-82122286%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aeaf422e55ada4a1ec11d8bb75aa496f13696ff0' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/standartSystemPageStyle1.html',
      1 => 1350627484,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '447515331508156d1c49728-82122286',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    'wgt_pluginMenu' => 0,
    'wgt_breadcrumb' => 0,
    '_header' => 0,
    '_content' => 0,
    '_footer' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d1c9b6b6_29542601',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d1c9b6b6_29542601')) {function content_508156d1c9b6b6_29542601($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars["wgt_pluginMenu"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_menuSystem), 0));?>

<?php $_smarty_tpl->tpl_vars["wgt_breadcrumb"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_breadcrumb, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>


<?php $_smarty_tpl->createLocalArrayVariable('_header', null, 0);
$_smarty_tpl->tpl_vars['_header']->value[] = $_smarty_tpl->tpl_vars['wgt_pluginMenu']->value;?>
<?php $_smarty_tpl->createLocalArrayVariable('_header', null, 0);
$_smarty_tpl->tpl_vars['_header']->value[] = $_smarty_tpl->tpl_vars['wgt_breadcrumb']->value;?>
 

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_standartPublicPageStyle1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>$_smarty_tpl->tpl_vars['_header']->value,'_content'=>$_smarty_tpl->tpl_vars['_content']->value,'_footer'=>$_smarty_tpl->tpl_vars['_footer']->value), 0);?>
<?php }} ?>