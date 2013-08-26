<?php /* Smarty version Smarty-3.1.11, created on 2013-08-27 02:03:38
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsFormButtons.html" */ ?>
<?php /*%%SmartyHeaderCode:3710681145206340d4a9134-85330943%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '01ac54aedbbf6a7f49e46e1954b36f6473958844' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1377558195,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3710681145206340d4a9134-85330943',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206340d4ef785_94695500',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_customCssClassNames' => 0,
    '_controlCssName' => 0,
    '_buttons' => 0,
    '_button' => 0,
    '_controlOwner' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206340d4ef785_94695500')) {function content_5206340d4ef785_94695500($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('FormButtons', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'control' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_customCssClassNames'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_customCssClassNames']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>


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
 <?php echo $_smarty_tpl->tpl_vars['_customCssClassNames']->value;?>
">
            <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_control".((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_controlOwner']->value)."Button".((string)$_smarty_tpl->tpl_vars['_button']->value)};?>

        </button>
    <?php } ?>
</div><?php }} ?>