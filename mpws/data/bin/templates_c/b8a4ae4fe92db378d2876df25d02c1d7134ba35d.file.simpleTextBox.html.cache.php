<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 11:50:28
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleTextBox.html" */ ?>
<?php /*%%SmartyHeaderCode:1272997852507695277b17c6-21271146%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b8a4ae4fe92db378d2876df25d02c1d7134ba35d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleTextBox.html',
      1 => 1350031794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1272997852507695277b17c6-21271146',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507695277c35b7_75670579',
  'variables' => 
  array (
    '_controlCssName' => 0,
    '_name' => 0,
    '_value' => 0,
    '_size' => 0,
    '_limit' => 0,
    '_controlCssNameCustom' => 0,
    '_controlValue' => 0,
    '_controlSize' => 0,
    '_controlLimit' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507695277c35b7_75670579')) {function content_507695277c35b7_75670579($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('TextBox', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(false, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlSize'] = new Smarty_variable(25, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlLimit'] = new Smarty_variable('', null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_value']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_size']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlSize'] = new Smarty_variable($_smarty_tpl->tpl_vars['_size']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_limit']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlLimit'] = new Smarty_variable(" maxlength=\"".((string)$_smarty_tpl->tpl_vars['_limit']->value)."\"", null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
    <input id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="text" name="mpws_field_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
" value="<?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
" size="<?php echo $_smarty_tpl->tpl_vars['_controlSize']->value;?>
"<?php echo $_smarty_tpl->tpl_vars['_controlLimit']->value;?>
 class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
</div><?php }} ?>