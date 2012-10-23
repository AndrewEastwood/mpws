<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 20:38:51
         compiled from "/var/www/mpws/web/default/v1.0/template/simple/text.html" */ ?>
<?php /*%%SmartyHeaderCode:28608444150863e950177d0-95321542%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45d5c5a0e0df4a959ac5bb1504842e87b284cc0b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/text.html',
      1 => 1351013530,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28608444150863e950177d0-95321542',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50863e95070264_13519800',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50863e95070264_13519800')) {function content_50863e95070264_13519800($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><span class="MPWSText">
    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)(($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'display' : $tmp))."_text".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
    
</span>
<?php }} ?>