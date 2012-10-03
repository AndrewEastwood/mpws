<?php /* Smarty version Smarty-3.1.11, created on 2012-10-03 23:14:55
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html" */ ?>
<?php /*%%SmartyHeaderCode:1040937366506c9b66056f05-62023748%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '001f27e0f6123b935501ab627af0711ee9f6c81f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html',
      1 => 1349295293,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1040937366506c9b66056f05-62023748',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506c9b6609ba53_72845355',
  'variables' => 
  array (
    'CURRENT' => 0,
    'OBJECT' => 0,
    '_type' => 0,
    '_title' => 0,
    'DISPLAY_OBJECT' => 0,
    '_details' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506c9b6609ba53_72845355')) {function content_506c9b6609ba53_72845355($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["DISPLAY_OBJECT"] = new Smarty_variable(glGetFirstNonEmptyValue($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT'],$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']), null, 0);?>
<div class="MPWSComponent MPWSComponentObjectSummary">
	<span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_type']->value)."_objectSummaryText".((string)$_smarty_tpl->tpl_vars['_title']->value)};?>
</span>
	<?php if (isset($_smarty_tpl->tpl_vars['_details']->value)){?>
	<span class="MPWSText MPWSTextDetails"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_type']->value)."_objectSummaryDetails`_details`"};?>
</span>
	<?php }?>
</div><?php }} ?>