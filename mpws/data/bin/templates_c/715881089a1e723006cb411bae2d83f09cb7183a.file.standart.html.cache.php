<?php /* Smarty version Smarty-3.1.11, created on 2012-09-26 21:32:07
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standart.html" */ ?>
<?php /*%%SmartyHeaderCode:1479222508506349428ac093-12633572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '715881089a1e723006cb411bae2d83f09cb7183a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standart.html',
      1 => 1348684321,
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
    'SITE' => 0,
    'header' => 0,
    'content' => 0,
    'footer' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506349428d65f6_04849643')) {function content_506349428d65f6_04849643($_smarty_tpl) {?>
<div class="MPWSPage MPWSPageStandart">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('data'=>$_smarty_tpl->tpl_vars['header']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_content, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('data'=>$_smarty_tpl->tpl_vars['content']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_footer, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('data'=>$_smarty_tpl->tpl_vars['footer']->value), 0);?>

</div><?php }} ?>