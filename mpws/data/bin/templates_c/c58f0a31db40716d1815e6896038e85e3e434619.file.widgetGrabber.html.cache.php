<?php /* Smarty version Smarty-3.1.11, created on 2012-10-13 00:45:04
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetGrabber.html" */ ?>
<?php /*%%SmartyHeaderCode:167684794450788f60e51a73-07625757%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c58f0a31db40716d1815e6896038e85e3e434619' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetGrabber.html',
      1 => 1349809493,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '167684794450788f60e51a73-07625757',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_widgets' => 0,
    'widgetEntry' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50788f60e5ed85_68671405',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50788f60e5ed85_68671405')) {function content_50788f60e5ed85_68671405($_smarty_tpl) {?>



<div class="MPWSComponent MPWSComponentWidgetGrabber">
<?php  $_smarty_tpl->tpl_vars['widgetEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['widgetEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['wgtKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_widgets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['widgetEntry']->key => $_smarty_tpl->tpl_vars['widgetEntry']->value){
$_smarty_tpl->tpl_vars['widgetEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['wgtKey']->value = $_smarty_tpl->tpl_vars['widgetEntry']->key;
?>
    <div class="MPWSSpacer MPWSWidgetSpace MPWSSpacerBefore"></div>
    <?php echo $_smarty_tpl->tpl_vars['widgetEntry']->value['HTML'];?>

    <div class="MPWSSpacer MPWSWidgetSpace MPWSSpacerAfter"></div>
<?php } ?>
</div><?php }} ?>