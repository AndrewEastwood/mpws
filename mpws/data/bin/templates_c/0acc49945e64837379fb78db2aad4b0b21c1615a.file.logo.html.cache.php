<?php /* Smarty version Smarty-3.1.11, created on 2012-10-09 00:11:28
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:173784669350734180051806-79995894%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '173784669350734180051806-79995894',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5073418005de15_81572544',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5073418005de15_81572544')) {function content_5073418005de15_81572544($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>