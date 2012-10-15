<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 22:32:20
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/divRowLabelValue.html" */ ?>
<?php /*%%SmartyHeaderCode:129159110650788f610398f1-06072320%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'df8c830f715dc57a9d4bd836a9b95a7bcf8da5f7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/divRowLabelValue.html',
      1 => 1350327225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '129159110650788f610398f1-06072320',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50788f61040fb8_38625655',
  'variables' => 
  array (
    '_label' => 0,
    '_value' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50788f61040fb8_38625655')) {function content_50788f61040fb8_38625655($_smarty_tpl) {?><div class="MPWSRowLabelValue">
    <span class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['_label']->value;?>
</span>
    <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
</span>
</div><?php }} ?>