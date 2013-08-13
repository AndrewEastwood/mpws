<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:37:40
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataRow.html" */ ?>
<?php /*%%SmartyHeaderCode:2042323165206341479b3d3-58831682%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '77e37e747b963f3d531054f4caa529e12b08f463' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataRow.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2042323165206341479b3d3-58831682',
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
  'unifunc' => 'content_520634147a50c1_82168757',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520634147a50c1_82168757')) {function content_520634147a50c1_82168757($_smarty_tpl) {?><div class="MPWSBlockDataRow">
    <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['_label']->value;?>
</span>
    <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
</span>
</div><?php }} ?>