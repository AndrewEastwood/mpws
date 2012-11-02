<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 20:55:12
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginMenu.html" */ ?>
<?php /*%%SmartyHeaderCode:138761277350804280cae059-39489088%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '652a3c755ae0ce425a3a6de1b0e183687c77be1c' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginMenu.html',
      1 => 1350579434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '138761277350804280cae059-39489088',
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
  'unifunc' => 'content_50804280ce50c1_96292784',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50804280ce50c1_96292784')) {function content_50804280ce50c1_96292784($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginMenuID" class="MPWSWidget MPWSWidgetSystemPluginMenu">
<?php $_smarty_tpl->tpl_vars["active_plugin_menu"] = new Smarty_variable($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])]->{"objectConfiguration_display_menuPlugin"}, null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['active_plugin_menu']->value)){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['active_plugin_menu']->value,'_OBJ'=>$_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])],'_showDescription'=>true), 0);?>

<?php }?>
</div><?php }} ?>