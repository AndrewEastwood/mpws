<?php /* Smarty version Smarty-3.1.11, created on 2012-10-17 13:22:54
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/systemPluginLinkMenu.html" */ ?>
<?php /*%%SmartyHeaderCode:472654241507e86fec85f56-80461228%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c31b4b24a2c756eba63db86372433bd8d94dd9a1' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/systemPluginLinkMenu.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '472654241507e86fec85f56-80461228',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'OBJECT' => 0,
    'active_plugin_menu' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507e86fed35ce3_55797163',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507e86fed35ce3_55797163')) {function content_507e86fed35ce3_55797163($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginLinkMenuID" class="MPWSWidget MPWSWidgetSystemPluginLinkMenu">
<?php $_smarty_tpl->tpl_vars["active_plugin_menu"] = new Smarty_variable($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])]->{"objectConfiguration_display_menuPlugin"}, null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['active_plugin_menu']->value)){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['active_plugin_menu']->value,'_OBJ'=>$_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])],'_showDescription'=>true), 0);?>

<?php }?>
</div><?php }} ?>