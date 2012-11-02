<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 21:06:37
         compiled from "/var/www/mpws/web/default/v1.0/template/simple/label.html" */ ?>
<?php /*%%SmartyHeaderCode:588024525508152b909c5b1-90761734%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '532b187efab104354fd4473020382d17605c376b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/label.html',
      1 => 1351015595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '588024525508152b909c5b1-90761734',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508152b90ddda5_51851109',
  'variables' => 
  array (
    '_for' => 0,
    '_resourceOwner' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508152b90ddda5_51851109')) {function content_508152b90ddda5_51851109($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
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