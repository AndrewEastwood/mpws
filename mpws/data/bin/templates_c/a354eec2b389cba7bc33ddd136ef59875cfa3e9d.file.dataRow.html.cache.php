<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:29:15
         compiled from "/var/www/mpws/web/default/v1.0/template/component/dataRow.html" */ ?>
<?php /*%%SmartyHeaderCode:2089482407508155abadfbe9-36936736%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a354eec2b389cba7bc33ddd136ef59875cfa3e9d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataRow.html',
      1 => 1350642978,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2089482407508155abadfbe9-36936736',
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
  'unifunc' => 'content_508155abb26664_45641473',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508155abb26664_45641473')) {function content_508155abb26664_45641473($_smarty_tpl) {?><div class="MPWSBlockDataRow">
    <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['_label']->value;?>
</span>
    <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
</span>
</div><?php }} ?>