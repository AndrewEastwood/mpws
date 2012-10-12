<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 12:17:00
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleDropDown.html" */ ?>
<?php /*%%SmartyHeaderCode:4313164715077de3ee482b2-40079391%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f441f4b1d1bb7f74b324d6e9b41b4f05b6eee5a8' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleDropDown.html',
      1 => 1350033415,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4313164715077de3ee482b2-40079391',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5077de3ee8ac89_07016273',
  'variables' => 
  array (
    '_controlCssName' => 0,
    '_name' => 0,
    '_items' => 0,
    '_controlCssNameCustom' => 0,
    '_controlItems' => 0,
    '_item' => 0,
    '_resource' => 0,
    '_ownerName' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5077de3ee8ac89_07016273')) {function content_5077de3ee8ac89_07016273($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('DropDown', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlItems'] = new Smarty_variable('', null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_items']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlItems'] = new Smarty_variable($_smarty_tpl->tpl_vars['_items']->value, null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
    <select id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="checkbox" name="mpws_field_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
" class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
        <?php  $_smarty_tpl->tpl_vars['_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_controlItems']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_item']->key => $_smarty_tpl->tpl_vars['_item']->value){
$_smarty_tpl->tpl_vars['_item']->_loop = true;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['_item']->value;?>
">
                <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value).((string)$_smarty_tpl->tpl_vars['_controlCssNameCustom']->value).((string)$_smarty_tpl->tpl_vars['_item']->value)};?>

            </option>
        <?php } ?>
    </select>
</div><?php }} ?>