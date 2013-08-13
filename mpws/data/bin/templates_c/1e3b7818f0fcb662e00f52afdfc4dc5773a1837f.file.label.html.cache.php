<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:37:33
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/label.html" */ ?>
<?php /*%%SmartyHeaderCode:1222204725206340d3bf4e9-07696675%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e3b7818f0fcb662e00f52afdfc4dc5773a1837f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/label.html',
      1 => 1372598117,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1222204725206340d3bf4e9-07696675',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_for' => 0,
    '_resourceOwner' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206340d3e44c2_48378927',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206340d3e44c2_48378927')) {function content_5206340d3e44c2_48378927($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
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