<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:37:40
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageHeader.html" */ ?>
<?php /*%%SmartyHeaderCode:203396535952063414b232f6-48609668%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa0374ee876c63be03d83fc175f0ba951ad8b0aa' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageHeader.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203396535952063414b232f6-48609668',
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
  'unifunc' => 'content_52063414b3db66_59028391',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52063414b3db66_59028391')) {function content_52063414b3db66_59028391($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenHeader">

<?php if ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_displayLogo){?>
	<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_logo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_macro_dataElements, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_data']->value), 0);?>

</div><?php }} ?>