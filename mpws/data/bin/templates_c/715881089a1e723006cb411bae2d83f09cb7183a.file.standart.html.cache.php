<?php /* Smarty version Smarty-3.1.11, created on 2012-09-29 17:10:34
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standart.html" */ ?>
<?php /*%%SmartyHeaderCode:1479222508506349428ac093-12633572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '715881089a1e723006cb411bae2d83f09cb7183a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standart.html',
      1 => 1348924036,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1479222508506349428ac093-12633572',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506349428d65f6_04849643',
  'variables' => 
  array (
    'INFO' => 0,
    'SITE' => 0,
    '_header' => 0,
    '_content' => 0,
    '_footer' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506349428d65f6_04849643')) {function content_506349428d65f6_04849643($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<div class="MPWSPage MPWSPageStandart MPWSPageStandart<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['DISPLAY'],0,1);?>
" id="MPWSPageStandart<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['DISPLAY'],0,1);?>
ID">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_header']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_content, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_content']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_footer, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_footer']->value), 0);?>

</div><?php }} ?>