<?php /* Smarty version Smarty-3.1.11, created on 2012-09-27 20:43:22
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:120276845150634996813e10-67140636%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '663f554cd257e4d3947bc3ec192716b71147a8a4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/footer.html',
      1 => 1348767794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '120276845150634996813e10-67140636',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506349968161d7_25971567',
  'variables' => 
  array (
    'data' => 0,
    'itemvar' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506349968161d7_25971567')) {function content_506349968161d7_25971567($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenFooter">
    
    <?php if (isset($_smarty_tpl->tpl_vars['data']->value)){?>
        <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
            <?php echo $_smarty_tpl->tpl_vars['itemvar']->value;?>

        <?php } ?>
    <?php }?>
</div><?php }} ?>