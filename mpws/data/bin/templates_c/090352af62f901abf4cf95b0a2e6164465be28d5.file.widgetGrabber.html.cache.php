<?php /* Smarty version Smarty-3.1.11, created on 2013-08-13 22:54:47
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/macro/widgetGrabber.html" */ ?>
<?php /*%%SmartyHeaderCode:665450445206341454cba4-76721050%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '090352af62f901abf4cf95b0a2e6164465be28d5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/macro/widgetGrabber.html',
      1 => 1376423674,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '665450445206341454cba4-76721050',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_520634145a1df0_08196256',
  'variables' => 
  array (
    '_widgets' => 0,
    'widgetEntry' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520634145a1df0_08196256')) {function content_520634145a1df0_08196256($_smarty_tpl) {?>



<div class="MPWSComponent MPWSComponentWidgetGrabber">
<?php  $_smarty_tpl->tpl_vars['widgetEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['widgetEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['wgtKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_widgets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['widgetEntry']->key => $_smarty_tpl->tpl_vars['widgetEntry']->value){
$_smarty_tpl->tpl_vars['widgetEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['wgtKey']->value = $_smarty_tpl->tpl_vars['widgetEntry']->key;
?>
    <div class="MPWSSpacer MPWSSpacerWidget MPWSSpacerBefore"></div>
    <?php echo $_smarty_tpl->tpl_vars['widgetEntry']->value['HTML'];?>

    <div class="MPWSSpacer MPWSSpacerWidget MPWSSpacerAfter"></div>
<?php } ?>
</div><?php }} ?>