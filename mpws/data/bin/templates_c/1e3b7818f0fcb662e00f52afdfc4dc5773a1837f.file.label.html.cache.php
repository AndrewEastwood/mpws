<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 22:06:15
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/label.html" */ ?>
<?php /*%%SmartyHeaderCode:17384569150817f1029b6f0-39427470%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e3b7818f0fcb662e00f52afdfc4dc5773a1837f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/label.html',
      1 => 1351018931,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17384569150817f1029b6f0-39427470',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50817f102c9569_26181581',
  'variables' => 
  array (
    '_for' => 0,
    '_resourceOwner' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f102c9569_26181581')) {function content_50817f102c9569_26181581($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>


<?php if (isset($_smarty_tpl->tpl_vars['_for']->value)){?>
<label for="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_for']->value;?>
ID">
<?php }else{ ?>
<label class="MPWSLabel">
<?php }?>
    <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_labelFor".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
</span>
</label><?php }} ?>