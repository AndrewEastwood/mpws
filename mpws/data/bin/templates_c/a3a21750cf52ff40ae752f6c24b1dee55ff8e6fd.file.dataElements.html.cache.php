<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:34:10
         compiled from "/var/www/mpws/web/default/v1.0/template/macro/dataElements.html" */ ?>
<?php /*%%SmartyHeaderCode:152373627508156d20e1ca0-46578641%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3a21750cf52ff40ae752f6c24b1dee55ff8e6fd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/macro/dataElements.html',
      1 => 1350627484,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '152373627508156d20e1ca0-46578641',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_data' => 0,
    'itemvar' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d21198e9_40253420',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d21198e9_40253420')) {function content_508156d21198e9_40253420($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentDataElements">
    
    <?php if (isset($_smarty_tpl->tpl_vars['_data']->value)){?>
        <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
?>
            <?php echo $_smarty_tpl->tpl_vars['itemvar']->value;?>

        <?php } ?>
    <?php }?>
</div>
<?php }} ?>