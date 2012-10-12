<?php /* Smarty version Smarty-3.1.11, created on 2012-10-11 12:44:23
         compiled from "/var/www/mpws/web/default/v1.0/template/page/standartPublicPageStyle1.html" */ ?>
<?php /*%%SmartyHeaderCode:275297611507694f76204f4-19175491%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0b85a9247966b25bd2cb88ee9ba5b7a90faed9b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/standartPublicPageStyle1.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '275297611507694f76204f4-19175491',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'CURRENT' => 0,
    '_header' => 0,
    '_content' => 0,
    '_footer' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507694f76586f2_11078900',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507694f76586f2_11078900')) {function content_507694f76586f2_11078900($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<div id="MPWSPageStandartPublic<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'],0,1);?>
<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'],0,1);?>
ID" class="MPWSPage MPWSPageStandartPublicPageStyle1 MPWSPageDisplay<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'],0,1);?>
 MPWSPage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'],0,1);?>
">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageHeader, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_header']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageContent, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_content']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageFooter, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_footer']->value), 0);?>

</div><?php }} ?>