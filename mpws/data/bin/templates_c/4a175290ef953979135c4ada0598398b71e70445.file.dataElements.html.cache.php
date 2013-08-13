<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:37:40
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/macro/dataElements.html" */ ?>
<?php /*%%SmartyHeaderCode:13558283052063414b80543-45039396%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a175290ef953979135c4ada0598398b71e70445' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/macro/dataElements.html',
      1 => 1350337286,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13558283052063414b80543-45039396',
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
  'unifunc' => 'content_52063414b9e0a2_11246522',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52063414b9e0a2_11246522')) {function content_52063414b9e0a2_11246522($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentDataElements">
    
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