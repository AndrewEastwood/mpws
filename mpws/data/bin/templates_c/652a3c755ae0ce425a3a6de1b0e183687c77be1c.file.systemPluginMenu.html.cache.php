<?php /* Smarty version Smarty-3.1.11, created on 2013-08-14 01:51:15
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginMenu.html" */ ?>
<?php /*%%SmartyHeaderCode:7982483475206346bb2fd62-81944628%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '652a3c755ae0ce425a3a6de1b0e183687c77be1c' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginMenu.html',
      1 => 1376434072,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7982483475206346bb2fd62-81944628',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206346bb66ca8_83888075',
  'variables' => 
  array (
    'INFO' => 0,
    'OBJECT' => 0,
    'active_plugin_menu' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206346bb66ca8_83888075')) {function content_5206346bb66ca8_83888075($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginMenuID" class="MPWSWidget MPWSWidgetSystemPluginMenu">
<?php $_smarty_tpl->tpl_vars["active_plugin_menu"] = new Smarty_variable($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])]->{"objectConfiguration_display_menuPlugin"}, null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['active_plugin_menu']->value)){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['active_plugin_menu']->value,'_OBJ'=>$_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])],'_showDescription'=>true), 0);?>

<?php }?>
</div><?php }} ?>