<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 19:10:14
         compiled from "/var/www/mpws/web/default/v1.0/template/component/objectSummary.html" */ ?>
<?php /*%%SmartyHeaderCode:1300932620507695053fa2f8-11801229%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b2aaf23207bc3ef5251be5270ea54d71f48d9a4e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/objectSummary.html',
      1 => 1350057923,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1300932620507695053fa2f8-11801229',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5076950541de13_18788470',
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
<?php if ($_valid && !is_callable('content_5076950541de13_18788470')) {function content_5076950541de13_18788470($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars["DISPLAY_OBJECT"] = new Smarty_variable(glGetFirstNonEmptyValue($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT'],$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']), null, 0);?>


<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_custom_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value), null, 0);?>

<div class="MPWSComponent MPWSComponentObjectSummary">
    <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."ObjectSummary"};?>
</span>
    <span class="MPWSText MPWSTextDetails"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."ObjectDescription"};?>
</span>
    <?php if (isset($_smarty_tpl->tpl_vars['_customText']->value)){?>
    <span class="MPWSText MPWSTextCustom"><?php echo $_smarty_tpl->tpl_vars['_customText']->value;?>
</span>
    <?php }?>
</div><?php }} ?>