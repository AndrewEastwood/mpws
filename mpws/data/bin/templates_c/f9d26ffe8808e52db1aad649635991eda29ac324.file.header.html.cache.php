<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:52
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/header.html" */ ?>
<?php /*%%SmartyHeaderCode:69573182150817f100c2ab3-24979185%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f9d26ffe8808e52db1aad649635991eda29ac324' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/header.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69573182150817f100c2ab3-24979185',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50817f100cebc8_77583734',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f100cebc8_77583734')) {function content_50817f100cebc8_77583734($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSHeader">
    <h3><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_display_header".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
</h3>
</div><?php }} ?>