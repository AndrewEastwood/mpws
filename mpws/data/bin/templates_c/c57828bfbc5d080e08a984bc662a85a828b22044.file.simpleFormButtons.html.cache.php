<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 20:46:03
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFormButtons.html" */ ?>
<?php /*%%SmartyHeaderCode:16941718665080405b55c386-96769482%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c57828bfbc5d080e08a984bc662a85a828b22044' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350494931,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16941718665080405b55c386-96769482',
  'function' => 
  array (
  ),
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
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5080405b589f27_66804489',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5080405b589f27_66804489')) {function content_5080405b589f27_66804489($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
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