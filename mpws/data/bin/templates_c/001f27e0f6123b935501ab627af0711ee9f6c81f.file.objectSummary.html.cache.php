<?php /* Smarty version Smarty-3.1.11, created on 2012-10-09 23:33:54
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html" */ ?>
<?php /*%%SmartyHeaderCode:17792754435073417fa7b3d9-45127021%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '001f27e0f6123b935501ab627af0711ee9f6c81f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html',
      1 => 1349814829,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17792754435073417fa7b3d9-45127021',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5073417fa9c622_66582119',
  'variables' => 
  array (
    'CURRENT' => 0,
    'OBJECT' => 0,
    '_ownerName' => 0,
    '__prop__' => 0,
    'DISPLAY_OBJECT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5073417fa9c622_66582119')) {function content_5073417fa9c622_66582119($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars["DISPLAY_OBJECT"] = new Smarty_variable(glGetFirstNonEmptyValue($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT'],$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']), null, 0);?>


<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_custom_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value), null, 0);?>

<div class="MPWSComponent MPWSComponentObjectSummary">
	<span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."ObjectSummary"};?>
</span>
	<span class="MPWSText MPWSTextDetails"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."ObjectDescription"};?>
</span>
</div><?php }} ?>