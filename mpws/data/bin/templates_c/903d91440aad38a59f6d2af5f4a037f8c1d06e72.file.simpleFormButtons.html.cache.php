<?php /* Smarty version Smarty-3.1.11, created on 2012-10-17 17:36:20
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleFormButtons.html" */ ?>
<?php /*%%SmartyHeaderCode:786082149507bb7533c9d53-89014774%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '903d91440aad38a59f6d2af5f4a037f8c1d06e72' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350484464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '786082149507bb7533c9d53-89014774',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507bb7533f37f0_70141309',
  'variables' => 
  array (
    '_page' => 0,
    '_controlCssName' => 0,
    '_buttons' => 0,
    '_button' => 0,
    '_controlPage' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507bb7533f37f0_70141309')) {function content_507bb7533f37f0_70141309($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('FormButtons', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlPage'] = new Smarty_variable('', null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['_page']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlPage'] = new Smarty_variable(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_page']->value), null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
">
    <?php  $_smarty_tpl->tpl_vars['_button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_button']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_buttons']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_button']->key => $_smarty_tpl->tpl_vars['_button']->value){
$_smarty_tpl->tpl_vars['_button']->_loop = true;
?>
        <button id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_button']->value;?>
ID" type="submit" name="do" value="<?php echo $_smarty_tpl->tpl_vars['_button']->value;?>
" class="MPWSControl MPWSControlButton<?php echo $_smarty_tpl->tpl_vars['_button']->value;?>
">
            <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_component_formPage".((string)$_smarty_tpl->tpl_vars['_controlPage']->value)."Button".((string)$_smarty_tpl->tpl_vars['_button']->value)};?>

        </button>
    <?php } ?>
</div><?php }} ?>