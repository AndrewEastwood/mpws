<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:47:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:17673302755068afd596f453-35905711%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '17673302755068afd596f453-35905711',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068afd597ac87_44190823',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd597ac87_44190823')) {function content_5068afd597ac87_44190823($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>