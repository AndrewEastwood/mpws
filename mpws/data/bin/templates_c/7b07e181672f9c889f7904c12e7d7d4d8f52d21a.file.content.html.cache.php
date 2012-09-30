<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:47:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/content.html" */ ?>
<?php /*%%SmartyHeaderCode:20408738905068afd5984d35-11036516%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b07e181672f9c889f7904c12e7d7d4d8f52d21a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/content.html',
      1 => 1349022472,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20408738905068afd5984d35-11036516',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE' => 0,
    '_data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068afd5996cb1_12124392',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd5996cb1_12124392')) {function content_5068afd5996cb1_12124392($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenContent">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_display_menu), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_dataElements, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_data']->value), 0);?>

</div><?php }} ?>