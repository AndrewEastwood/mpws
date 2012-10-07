<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 14:59:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:14641370450716ea28f3ba9-24244030%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0acc49945e64837379fb78db2aad4b0b21c1615a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html',
      1 => 1349288503,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14641370450716ea28f3ba9-24244030',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50716ea28ff5d4_24389386',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50716ea28ff5d4_24389386')) {function content_50716ea28ff5d4_24389386($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>