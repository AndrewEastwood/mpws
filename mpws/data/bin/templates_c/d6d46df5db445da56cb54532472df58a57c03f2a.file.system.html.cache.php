<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 14:59:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/system.html" */ ?>
<?php /*%%SmartyHeaderCode:69451153050716ea2617344-58701175%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd6d46df5db445da56cb54532472df58a57c03f2a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/system.html',
      1 => 1349287120,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69451153050716ea2617344-58701175',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    'wgt_sysUsrInfo' => 0,
    'msg_common' => 0,
    'INFO' => 0,
    'wgt_tools' => 0,
    'wgt_menuList' => 0,
    '_header' => 0,
    '_headers' => 0,
    '_content' => 0,
    '_contents' => 0,
    '_footer' => 0,
    '_footers' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50716ea26939e4_92380814',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50716ea26939e4_92380814')) {function content_50716ea26939e4_92380814($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars["wgt_sysUsrInfo"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_widget_systemUserInfo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

<?php $_smarty_tpl->tpl_vars["msg_common"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_message, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_realm'=>'COMMON'), 0));?>


<?php $_smarty_tpl->createLocalArrayVariable('_headers', null, 0);
$_smarty_tpl->tpl_vars['_headers']->value[] = $_smarty_tpl->tpl_vars['wgt_sysUsrInfo']->value;?>
<?php $_smarty_tpl->createLocalArrayVariable('_contents', null, 0);
$_smarty_tpl->tpl_vars['_contents']->value[] = $_smarty_tpl->tpl_vars['msg_common']->value;?>
<?php $_smarty_tpl->tpl_vars['_footers'] = new Smarty_variable(Array(), null, 0);?>

<?php if (empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])){?>
    <?php $_smarty_tpl->tpl_vars["wgt_tools"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_widget_systemPluginLinkList, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

    <?php $_smarty_tpl->createLocalArrayVariable('_contents', null, 0);
$_smarty_tpl->tpl_vars['_contents']->value[] = $_smarty_tpl->tpl_vars['wgt_tools']->value;?>
<?php }else{ ?>
    
    <?php if (empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'])){?>
        <?php $_smarty_tpl->tpl_vars["wgt_menuList"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_widget_systemPluginLinkMenu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

        <?php $_smarty_tpl->createLocalArrayVariable('_contents', null, 0);
$_smarty_tpl->tpl_vars['_contents']->value[] = $_smarty_tpl->tpl_vars['wgt_menuList']->value;?>
    <?php }?>
<?php }?>


<?php if (isset($_smarty_tpl->tpl_vars['_header']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_headers'] = new Smarty_variable(array_merge($_smarty_tpl->tpl_vars['_headers']->value,$_smarty_tpl->tpl_vars['_header']->value), null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_content']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_contents'] = new Smarty_variable(array_merge($_smarty_tpl->tpl_vars['_contents']->value,$_smarty_tpl->tpl_vars['_content']->value), null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_footer']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_footers'] = new Smarty_variable(array_merge($_smarty_tpl->tpl_vars['_footers']->value,$_smarty_tpl->tpl_vars['_footer']->value), null, 0);?>
<?php }?>


<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_standartSystemPageStyle1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_header'=>$_smarty_tpl->tpl_vars['_headers']->value,'_content'=>$_smarty_tpl->tpl_vars['_contents']->value,'_footer'=>$_smarty_tpl->tpl_vars['_footers']->value), 0);?>
<?php }} ?>