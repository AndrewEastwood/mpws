<?php /* Smarty version Smarty-3.1.11, created on 2012-09-26 22:08:36
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/header.html" */ ?>
<?php /*%%SmartyHeaderCode:40160002350634996800435-65038860%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a297d65371600e951f5bf16207ed21a2b5fd17a0' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/header.html',
      1 => 1348686514,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '40160002350634996800435-65038860',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50634996802227_06926130',
  'variables' => 
  array (
    'SITE' => 0,
    'data' => 0,
    'itemvar' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50634996802227_06926130')) {function content_50634996802227_06926130($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenHeader">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_component_logo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    
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