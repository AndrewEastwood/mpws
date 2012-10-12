<?php /* Smarty version Smarty-3.1.11, created on 2012-10-11 12:44:23
         compiled from "/var/www/mpws/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:1311701308507694f7677804-54565430%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '1311701308507694f7677804-54565430',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507694f7682651_40934208',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507694f7682651_40934208')) {function content_507694f7682651_40934208($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>