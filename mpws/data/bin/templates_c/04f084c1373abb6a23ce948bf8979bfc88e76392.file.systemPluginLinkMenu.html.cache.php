<?php /* Smarty version Smarty-3.1.11, created on 2012-10-02 22:15:59
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkMenu.html" */ ?>
<?php /*%%SmartyHeaderCode:1244959285506b3b7a0d7192-77713274%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04f084c1373abb6a23ce948bf8979bfc88e76392' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkMenu.html',
      1 => 1349205357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1244959285506b3b7a0d7192-77713274',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506b3b7a137fb1_75544515',
  'variables' => 
  array (
    'active_plugin_name' => 0,
    'WOB' => 0,
    'active_plugin_menu' => 0,
    'SITE' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506b3b7a137fb1_75544515')) {function content_506b3b7a137fb1_75544515($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginLinkMenuID" class="MPWSWidget MPWSWidgetSystemPluginLinkMenu">
<?php $_smarty_tpl->tpl_vars["active_plugin_name"] = new Smarty_variable(libraryRequest::getPlugin(), null, 0);?>
<?php $_smarty_tpl->tpl_vars["active_plugin_menu"] = new Smarty_variable($_smarty_tpl->tpl_vars['WOB']->value[makeKey($_smarty_tpl->tpl_vars['active_plugin_name']->value)]->{"objectConfiguration_display_menuPlugin"}, null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['active_plugin_menu']->value)){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['active_plugin_menu']->value,'_do'=>$_smarty_tpl->tpl_vars['WOB']->value[makeKey($_smarty_tpl->tpl_vars['active_plugin_name']->value)],'_showDescription'=>true), 0);?>

<?php }?>

</div><?php }} ?>