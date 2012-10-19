<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:59
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataRow.html" */ ?>
<?php /*%%SmartyHeaderCode:112821297650817f17177ec2-36378264%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '112821297650817f17177ec2-36378264',
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
  'unifunc' => 'content_50817f171818a2_06845973',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f171818a2_06845973')) {function content_50817f171818a2_06845973($_smarty_tpl) {?><div class="MPWSBlockDataRow">
    <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['_label']->value;?>
</span>
    <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
</span>
</div><?php }} ?>