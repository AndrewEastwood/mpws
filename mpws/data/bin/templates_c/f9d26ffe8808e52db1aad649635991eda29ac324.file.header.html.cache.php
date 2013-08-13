<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:37:33
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/header.html" */ ?>
<?php /*%%SmartyHeaderCode:13478782425206340d13d066-09863852%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f9d26ffe8808e52db1aad649635991eda29ac324' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/header.html',
      1 => 1351538379,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13478782425206340d13d066-09863852',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_title' => 0,
    '_resourceOwner' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206340d158d13_59313962',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206340d158d13_59313962')) {function content_5206340d158d13_59313962($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSHeader">
    <h3><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_title']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)(($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'display' : $tmp))."_header".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))} : $tmp);?>
</h3>
</div><?php }} ?>