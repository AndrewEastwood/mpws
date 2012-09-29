<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 00:00:35
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html" */ ?>
<?php /*%%SmartyHeaderCode:7036684506705d510cfb9-93843610%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '48a01374c6045ce1f4ded1f4efca0a227fdf65db' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html',
      1 => 1348952433,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7036684506705d510cfb9-93843610',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506705d51694c4_39973906',
  'variables' => 
  array (
    'SITE' => 0,
    'wgt_sysUsrInfo' => 0,
    'msg_common' => 0,
    'MODEL' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506705d51694c4_39973906')) {function content_506705d51694c4_39973906($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["wgt_sysUsrInfo"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_widget_systemUserInfo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

<?php $_smarty_tpl->tpl_vars["msg_common"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_message, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_realm'=>'COMMON'), 0));?>


<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->{"objectTemplatePath_page_index:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>array($_smarty_tpl->tpl_vars['wgt_sysUsrInfo']->value),'_content'=>array($_smarty_tpl->tpl_vars['msg_common']->value,$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']['ACTIVE_USERS']['HTML']),'_footer'=>array()), 0);?>
<?php }} ?>