<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:55
         compiled from "/var/www/mpws/web/default/v1.0/template/component/dataElements.html" */ ?>
<?php /*%%SmartyHeaderCode:1198651002507ba5e30225b2-48745433%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bc7728ca62f9b2e19f11d332f240db36cc846e6e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataElements.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1198651002507ba5e30225b2-48745433',
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
  'unifunc' => 'content_507ba5e3042243_35260614',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e3042243_35260614')) {function content_507ba5e3042243_35260614($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentDataElements">
    
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