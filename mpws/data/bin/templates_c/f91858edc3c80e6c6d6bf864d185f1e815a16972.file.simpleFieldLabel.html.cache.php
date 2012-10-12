<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 11:48:06
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleFieldLabel.html" */ ?>
<?php /*%%SmartyHeaderCode:16594960265077d1049937c2-27869773%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f91858edc3c80e6c6d6bf864d185f1e815a16972' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350031670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16594960265077d1049937c2-27869773',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5077d1049ca749_26988001',
  'variables' => 
  array (
    '_labelKey' => 0,
    '_controlType' => 0,
    '_controlName' => 0,
    '_resource' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5077d1049ca749_26988001')) {function content_5077d1049ca749_26988001($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSFieldLabel MPWSFieldLabel<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_labelKey']->value);?>
">
    <label for="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlType']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
ID">
        <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_labelKey']->value)};?>
</span>
    </label>
</div><?php }} ?>