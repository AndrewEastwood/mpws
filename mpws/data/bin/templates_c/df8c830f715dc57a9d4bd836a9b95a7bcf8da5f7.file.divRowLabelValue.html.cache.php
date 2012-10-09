<?php /* Smarty version Smarty-3.1.11, created on 2012-10-09 22:43:52
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/divRowLabelValue.html" */ ?>
<?php /*%%SmartyHeaderCode:102051911250747e7821b671-29074161%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'df8c830f715dc57a9d4bd836a9b95a7bcf8da5f7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/divRowLabelValue.html',
      1 => 1349810994,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '102051911250747e7821b671-29074161',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_label' => 0,
    '_value' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50747e78229165_67117354',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50747e78229165_67117354')) {function content_50747e78229165_67117354($_smarty_tpl) {?><div class="MPWSRowLabelValue">
    <span class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['_label']->value;?>
</span>
    <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
</span>
</div><?php }} ?>