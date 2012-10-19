<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:14:17
         compiled from "/var/www/mpws/web/default/v1.0/template/simple/header.html" */ ?>
<?php /*%%SmartyHeaderCode:92589112550815229319e17-23411956%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9e48bbe01dbc0e9e1875b6afe94b60600cbec79' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/header.html',
      1 => 1350642619,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92589112550815229319e17-23411956',
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
  'unifunc' => 'content_50815229326de4_93156216',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50815229326de4_93156216')) {function content_50815229326de4_93156216($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSHeader">
    <h3><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_display_header".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
</h3>
</div><?php }} ?>