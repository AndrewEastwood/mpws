<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:34:10
         compiled from "/var/www/mpws/web/default/v1.0/template/component/pageHeader.html" */ ?>
<?php /*%%SmartyHeaderCode:1575468285508156d20aec21-64351015%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3c3cdeb9c5b37312bca6c3c4d198cf1985f5226b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pageHeader.html',
      1 => 1350641198,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1575468285508156d20aec21-64351015',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    '_data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d20c9208_71330710',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d20c9208_71330710')) {function content_508156d20c9208_71330710($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenHeader">

<?php if ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_displayLogo){?>
	<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_logo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_macro_dataElements, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_data']->value), 0);?>

</div><?php }} ?>