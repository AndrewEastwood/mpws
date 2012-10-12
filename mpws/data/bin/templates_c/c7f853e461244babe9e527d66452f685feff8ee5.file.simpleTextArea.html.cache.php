<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 11:50:28
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleTextArea.html" */ ?>
<?php /*%%SmartyHeaderCode:15187254035077b5ad753ca4-60099716%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c7f853e461244babe9e527d66452f685feff8ee5' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleTextArea.html',
      1 => 1350031807,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15187254035077b5ad753ca4-60099716',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5077b5ad7ace29_02077138',
  'variables' => 
  array (
    '_controlCssName' => 0,
    '_name' => 0,
    '_value' => 0,
    '_cols' => 0,
    '_rows' => 0,
    '_controlCssNameCustom' => 0,
    '_controlColSize' => 0,
    '_controlRowSize' => 0,
    '_controlValue' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5077b5ad7ace29_02077138')) {function content_5077b5ad7ace29_02077138($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('TextArea', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(false, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlColSize'] = new Smarty_variable(45, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRowSize'] = new Smarty_variable(6, null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_value']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_cols']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlColSize'] = new Smarty_variable($_smarty_tpl->tpl_vars['_cols']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_rows']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlRowSize'] = new Smarty_variable($_smarty_tpl->tpl_vars['_rows']->value, null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
    <textarea id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" name="mpws_field_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
" cols="<?php echo $_smarty_tpl->tpl_vars['_controlColSize']->value;?>
" rows="<?php echo $_smarty_tpl->tpl_vars['_controlRowSize']->value;?>
" class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
</textarea>
</div><?php }} ?>