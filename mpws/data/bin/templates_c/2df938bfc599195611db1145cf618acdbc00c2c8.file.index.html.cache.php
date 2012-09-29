<?php /* Smarty version Smarty-3.1.11, created on 2012-09-29 17:28:23
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/index.html" */ ?>
<?php /*%%SmartyHeaderCode:14254013715067015a547630-42347251%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2df938bfc599195611db1145cf618acdbc00c2c8' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/index.html',
      1 => 1348928895,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14254013715067015a547630-42347251',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5067015a5a63e6_37795146',
  'variables' => 
  array (
    'SITE' => 0,
    'wgt_sysUsrInfo' => 0,
    'msg_common' => 0,
    'MODEL' => 0,
    'WOB' => 0,
    'webObj' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5067015a5a63e6_37795146')) {function content_5067015a5a63e6_37795146($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["wgt_sysUsrInfo"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_widget_systemUserInfo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

<?php $_smarty_tpl->tpl_vars["msg_common"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_message, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_realm'=>'COMMON'), 0));?>


<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->{"objectTemplatePath_page_index:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>array($_smarty_tpl->tpl_vars['wgt_sysUsrInfo']->value),'_content'=>array($_smarty_tpl->tpl_vars['msg_common']->value),'_footer'=>array()), 0);?>


<?php echo $_smarty_tpl->tpl_vars['MODEL']->value['CUSTOM']['DEMO'];?>


<?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['WOB']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
    <?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_display_displayPropTest;?>

<?php } ?><?php }} ?>