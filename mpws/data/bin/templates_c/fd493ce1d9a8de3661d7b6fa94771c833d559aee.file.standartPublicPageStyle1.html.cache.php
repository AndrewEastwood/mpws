<?php /* Smarty version Smarty-3.1.11, created on 2013-08-18 22:12:28
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPublicPageStyle1.html" */ ?>
<?php /*%%SmartyHeaderCode:30309976052063414ad1df6-06263032%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fd493ce1d9a8de3661d7b6fa94771c833d559aee' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPublicPageStyle1.html',
      1 => 1376853146,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '30309976052063414ad1df6-06263032',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_52063414b0d4f1_88550194',
  'variables' => 
  array (
    'INFO' => 0,
    'CURRENT' => 0,
    '_header' => 0,
    '_content' => 0,
    '_footer' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52063414b0d4f1_88550194')) {function content_52063414b0d4f1_88550194($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<div id="MPWSPageStandartPublic<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'],0,1);?>
<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'],0,1);?>
ID" class="MPWSPage MPWSPageStandartPublicPageStyle1 MPWSPagePlugin<?php echo smarty_modifier_capitalize((($tmp = @$_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])===null||$tmp==='' ? 'None' : $tmp),0,1);?>
 MPWSPageDisplay<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'],0,1);?>
 MPWSPage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'],0,1);?>
">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageHeader, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_header']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageContent, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_content']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageFooter, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_footer']->value), 0);?>

</div><?php }} ?>