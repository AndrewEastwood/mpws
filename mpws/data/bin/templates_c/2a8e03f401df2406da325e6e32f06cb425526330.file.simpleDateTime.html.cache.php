<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 11:49:15
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleDateTime.html" */ ?>
<?php /*%%SmartyHeaderCode:11188579095077be5f67ede6-63063346%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a8e03f401df2406da325e6e32f06cb425526330' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleDateTime.html',
      1 => 1350031721,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11188579095077be5f67ede6-63063346',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5077be5f6bdd48_69546631',
  'variables' => 
  array (
    '_controlCssName' => 0,
    '_name' => 0,
    '_value' => 0,
    '_controlCssNameCustom' => 0,
    '_controlValue' => 0,
    '_controlSize' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5077be5f6bdd48_69546631')) {function content_5077be5f6bdd48_69546631($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('DateTime', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(false, null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_value']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
    <input id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="text" name="mpws_field_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
" value="<?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
" size="<?php echo $_smarty_tpl->tpl_vars['_controlSize']->value;?>
" class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
</div><?php }} ?>