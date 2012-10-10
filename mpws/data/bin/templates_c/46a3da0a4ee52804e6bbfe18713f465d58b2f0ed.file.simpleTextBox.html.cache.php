<?php /* Smarty version Smarty-3.1.11, created on 2012-10-10 22:47:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleTextBox.html" */ ?>
<?php /*%%SmartyHeaderCode:20596837055075d0b60c3f59-44269040%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46a3da0a4ee52804e6bbfe18713f465d58b2f0ed' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleTextBox.html',
      1 => 1349898431,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20596837055075d0b60c3f59-44269040',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5075d0b61207b5_83821634',
  'variables' => 
  array (
    '_name' => 0,
    '_value' => 0,
    '_size' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5075d0b61207b5_83821634')) {function content_5075d0b61207b5_83821634($_smarty_tpl) {?><div class="MPWSControlField MPWSControlFieldTextBox">
    <input type="text" name="mpws_field_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
" value="<?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
" size="<?php echo $_smarty_tpl->tpl_vars['_size']->value;?>
">
</div><?php }} ?>