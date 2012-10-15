<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 10:12:19
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleFieldLabel.html" */ ?>
<?php /*%%SmartyHeaderCode:1813123178507bb7531466f1-37719356%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '1813123178507bb7531466f1-37719356',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_labelKey' => 0,
    '_controlType' => 0,
    '_controlName' => 0,
    '_resource' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507bb7531d1fa3_30478127',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507bb7531d1fa3_30478127')) {function content_507bb7531d1fa3_30478127($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSFieldLabel MPWSFieldLabel<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_labelKey']->value);?>
">
    <label for="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlType']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
ID">
        <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_labelKey']->value)};?>
</span>
    </label>
</div><?php }} ?>