<?php /* Smarty version Smarty-3.1.11, created on 2013-08-24 21:21:48
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:10238460452063414b56001-45503565%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0acc49945e64837379fb78db2aad4b0b21c1615a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html',
      1 => 1377368505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10238460452063414b56001-45503565',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_52063414b6a346_02479597',
  'variables' => 
  array (
    'CURRENT' => 0,
    'INFO' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52063414b6a346_02479597')) {function content_52063414b6a346_02479597($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_INTERNAL_C'];?>
<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>