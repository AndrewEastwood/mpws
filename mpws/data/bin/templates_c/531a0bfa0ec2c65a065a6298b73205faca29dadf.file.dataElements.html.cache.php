<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:47:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataElements.html" */ ?>
<?php /*%%SmartyHeaderCode:5128797715068afd59f9e47-88066256%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '531a0bfa0ec2c65a065a6298b73205faca29dadf' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataElements.html',
      1 => 1349022504,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5128797715068afd59f9e47-88066256',
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
  'unifunc' => 'content_5068afd5a0b628_86682583',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd5a0b628_86682583')) {function content_5068afd5a0b628_86682583($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentDataElements">
    
    <?php if (isset($_smarty_tpl->tpl_vars['_data']->value)){?>
        <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
?>
            <div class="MPWSBlock"><?php echo $_smarty_tpl->tpl_vars['itemvar']->value;?>
</div>
        <?php } ?>
    <?php }?>
</div>
<?php }} ?>