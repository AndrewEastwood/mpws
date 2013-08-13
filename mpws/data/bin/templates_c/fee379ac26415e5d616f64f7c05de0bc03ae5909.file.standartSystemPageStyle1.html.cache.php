<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:37:40
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartSystemPageStyle1.html" */ ?>
<?php /*%%SmartyHeaderCode:5776262385206341489e712-58700965%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fee379ac26415e5d616f64f7c05de0bc03ae5909' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartSystemPageStyle1.html',
      1 => 1350590328,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5776262385206341489e712-58700965',
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
  'unifunc' => 'content_520634148c7005_25858454',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520634148c7005_25858454')) {function content_520634148c7005_25858454($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars["wgt_pluginMenu"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_menuSystem), 0));?>

<?php $_smarty_tpl->tpl_vars["wgt_breadcrumb"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_breadcrumb, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>


<?php $_smarty_tpl->createLocalArrayVariable('_header', null, 0);
$_smarty_tpl->tpl_vars['_header']->value[] = $_smarty_tpl->tpl_vars['wgt_pluginMenu']->value;?>
<?php $_smarty_tpl->createLocalArrayVariable('_header', null, 0);
$_smarty_tpl->tpl_vars['_header']->value[] = $_smarty_tpl->tpl_vars['wgt_breadcrumb']->value;?>
 

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_standartPublicPageStyle1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>$_smarty_tpl->tpl_vars['_header']->value,'_content'=>$_smarty_tpl->tpl_vars['_content']->value,'_footer'=>$_smarty_tpl->tpl_vars['_footer']->value), 0);?>
<?php }} ?>