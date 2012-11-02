<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 23:20:33
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/text.html" */ ?>
<?php /*%%SmartyHeaderCode:2873872385085a1159a8e09-71739132%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1daef6f887b78ac9f3ad513a592d120729febb89' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/text.html',
      1 => 1351018931,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2873872385085a1159a8e09-71739132',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5085a1159c2ff9_99824852',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5085a1159c2ff9_99824852')) {function content_5085a1159c2ff9_99824852($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><span class="MPWSText">
    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)(($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'display' : $tmp))."_text".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
    
</span>
<?php }} ?>