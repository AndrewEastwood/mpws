<?php /* Smarty version Smarty-3.1.11, created on 2012-09-26 21:41:35
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:93442475450634b6f9a37b4-24156781%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0acc49945e64837379fb78db2aad4b0b21c1615a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html',
      1 => 1348684890,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '93442475450634b6f9a37b4-24156781',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50634b6f9b4bb0_81497445',
  'variables' => 
  array (
    'SITE' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50634b6f9b4bb0_81497445')) {function content_50634b6f9b4bb0_81497445($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>