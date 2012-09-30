<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:56:18
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/tools.html" */ ?>
<?php /*%%SmartyHeaderCode:15403799845068afd5831bb0-23182188%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a2bd7a905030f8591c53d6372450316d46fb9b7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/tools.html',
      1 => 1349038559,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15403799845068afd5831bb0-23182188',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068afd584c428_62623488',
  'variables' => 
  array (
    'SITE' => 0,
    'msg_common' => 0,
    'active_plugin_name' => 0,
    'wgt_tools' => 0,
    'wgt_sysUsrInfo' => 0,
    '_contents' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd584c428_62623488')) {function content_5068afd584c428_62623488($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["wgt_sysUsrInfo"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_widget_systemUserInfo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

<?php $_smarty_tpl->tpl_vars["msg_common"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_message, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_realm'=>'COMMON'), 0));?>


<?php $_smarty_tpl->createLocalArrayVariable('_contents', null, 0);
$_smarty_tpl->tpl_vars['_contents']->value[] = $_smarty_tpl->tpl_vars['msg_common']->value;?>
<?php $_smarty_tpl->tpl_vars["active_plugin_name"] = new Smarty_variable(libraryRequest::getPlugin(), null, 0);?>

<?php if (empty($_smarty_tpl->tpl_vars['active_plugin_name']->value)){?>
    <?php $_smarty_tpl->tpl_vars["wgt_tools"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_widget_systemPluginLinkList, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

    <?php $_smarty_tpl->createLocalArrayVariable('_contents', null, 0);
$_smarty_tpl->tpl_vars['_contents']->value[] = $_smarty_tpl->tpl_vars['wgt_tools']->value;?>
<?php }else{ ?>
SOME 

<?php }?>
    
    
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->{"objectTemplatePath_page_index:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>array($_smarty_tpl->tpl_vars['wgt_sysUsrInfo']->value),'_content'=>$_smarty_tpl->tpl_vars['_contents']->value,'_footer'=>array()), 0);?>
<?php }} ?>