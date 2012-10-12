<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 11:49:15
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleCheckBox.html" */ ?>
<?php /*%%SmartyHeaderCode:8382766735077ba1a7d39c0-12920749%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'df5c9629f3b0c7daeacd3f944cc923beadf9ee32' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleCheckBox.html',
      1 => 1350031727,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8382766735077ba1a7d39c0-12920749',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5077ba1a80ad62_70310268',
  'variables' => 
  array (
    '_controlCssName' => 0,
    '_name' => 0,
    '_value' => 0,
    '_controlCssNameCustom' => 0,
    '_controlValue' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5077ba1a80ad62_70310268')) {function content_5077ba1a80ad62_70310268($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('CheckBox', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable('', null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_value']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(" checked=\"".((string)$_smarty_tpl->tpl_vars['_value']->value)."\"", null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
    <input id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="checkbox" name="mpws_field_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
"<?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
 class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
</div><?php }} ?>