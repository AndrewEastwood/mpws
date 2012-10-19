<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:25:13
         compiled from "/var/www/mpws/web/default/v1.0/template/control/mpwsFormButtons.html" */ ?>
<?php /*%%SmartyHeaderCode:1850520211508152f79a09d9-67931221%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '331b902f5b3f9259cd18347d4cf8618679da3a53' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1350653018,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1850520211508152f79a09d9-67931221',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508152f7a57555_15823451',
  'variables' => 
  array (
    '_ownerName' => 0,
    '_controlCssName' => 0,
    '_buttons' => 0,
    '_button' => 0,
    '_controlOwnerName' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508152f7a57555_15823451')) {function content_508152f7a57555_15823451($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('FormButtons', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlOwnerName'] = new Smarty_variable(smarty_modifier_capitalize((($tmp = @$_smarty_tpl->tpl_vars['_ownerName']->value)===null||$tmp==='' ? '' : $tmp)), null, 0);?>


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
            <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_component_controlButton".((string)$_smarty_tpl->tpl_vars['_controlOwnerName']->value).((string)$_smarty_tpl->tpl_vars['_button']->value)};?>

        </button>
    <?php } ?>
</div><?php }} ?>