<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 22:32:20
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:33801246250788f612bb036-17190593%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0acc49945e64837379fb78db2aad4b0b21c1615a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html',
      1 => 1350327225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33801246250788f612bb036-17190593',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50788f612c72c0_01739375',
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50788f612c72c0_01739375')) {function content_50788f612c72c0_01739375($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>