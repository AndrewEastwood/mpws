<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:54
         compiled from "/var/www/mpws/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:1096462134507ba5e3008947-57262061%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f351b33933fd3cab08081271d1ff0192b3d7b9f' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/logo.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1096462134507ba5e3008947-57262061',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5e3018ed4_26428973',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e3018ed4_26428973')) {function content_507ba5e3018ed4_26428973($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>