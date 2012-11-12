<?php /* Smarty version Smarty-3.1.11, created on 2012-10-29 15:39:30
         compiled from "/var/www/mpws/web/default/v1.0/template/control/mpwsLabelValue.html" */ ?>
<?php /*%%SmartyHeaderCode:208290948508e8712588a48-03077808%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0190d1a14dae6554df77ee4e77e71bcb9d8d3bab' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsLabelValue.html',
      1 => 1351517829,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '208290948508e8712588a48-03077808',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_controlCssName' => 0,
    '_name' => 0,
    '_value' => 0,
    '_controlCssNameCustom' => 0,
    '_controlValue' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508e87125ab2b9_60536757',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508e87125ab2b9_60536757')) {function content_508e87125ab2b9_60536757($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('LabelValue', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>


<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
    
    <span class="MPWSControlReadOnlyValue"><?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
</span>
</div><?php }} ?>