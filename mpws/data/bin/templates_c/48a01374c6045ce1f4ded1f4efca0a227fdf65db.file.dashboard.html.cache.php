<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:54:02
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html" */ ?>
<?php /*%%SmartyHeaderCode:16782814785068b065beb6d3-07346943%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '48a01374c6045ce1f4ded1f4efca0a227fdf65db' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html',
      1 => 1349038353,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16782814785068b065beb6d3-07346943',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068b065c40f12_33628772',
  'variables' => 
  array (
    'SITE' => 0,
    'MODEL' => 0,
    'wgt_sysUsrInfo' => 0,
    'msg_common' => 0,
    'wgt_grabbed' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068b065c40f12_33628772')) {function content_5068b065c40f12_33628772($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["wgt_sysUsrInfo"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_widget_systemUserInfo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

<?php $_smarty_tpl->tpl_vars["msg_common"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_message, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_realm'=>'COMMON'), 0));?>

<?php $_smarty_tpl->tpl_vars["wgt_grabbed"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->{"objectTemplatePath_page_index:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>array($_smarty_tpl->tpl_vars['wgt_sysUsrInfo']->value),'_content'=>array($_smarty_tpl->tpl_vars['msg_common']->value,$_smarty_tpl->tpl_vars['wgt_grabbed']->value),'_footer'=>array()), 0);?>
<?php }} ?>