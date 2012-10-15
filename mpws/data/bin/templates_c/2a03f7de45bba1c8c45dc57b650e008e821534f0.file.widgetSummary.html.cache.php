<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 23:37:48
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetSummary.html" */ ?>
<?php /*%%SmartyHeaderCode:299401021507c74028c6fc6-71057103%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a03f7de45bba1c8c45dc57b650e008e821534f0' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetSummary.html',
      1 => 1350333464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '299401021507c74028c6fc6-71057103',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507c74028ecd87_42119970',
  'variables' => 
  array (
    'CURRENT' => 0,
    'OBJECT' => 0,
    '_ownerName' => 0,
    '__prop__' => 0,
    'DISPLAY_OBJECT' => 0,
    '_customText' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507c74028ecd87_42119970')) {function content_507c74028ecd87_42119970($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars["DISPLAY_OBJECT"] = new Smarty_variable(glGetFirstNonEmptyValue($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT'],$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']), null, 0);?>


<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_custom_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value), null, 0);?>

<div class="MPWSComponent MPWSComponentWidgetSummary">
    <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."WidgetSummary"};?>
</span>
    <span class="MPWSText MPWSTextDetails"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."WidgetDescription"};?>
</span>
    <?php if (isset($_smarty_tpl->tpl_vars['_customText']->value)){?>
    <span class="MPWSText MPWSTextCustom"><?php echo $_smarty_tpl->tpl_vars['_customText']->value;?>
</span>
    <?php }?>
</div><?php }} ?>