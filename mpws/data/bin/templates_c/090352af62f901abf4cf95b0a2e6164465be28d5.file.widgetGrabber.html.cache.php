<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:26:03
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/macro/widgetGrabber.html" */ ?>
<?php /*%%SmartyHeaderCode:203714947850817f1bbe51b0-58482861%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '090352af62f901abf4cf95b0a2e6164465be28d5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/macro/widgetGrabber.html',
      1 => 1350579434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203714947850817f1bbe51b0-58482861',
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
  'unifunc' => 'content_50817f1bc08581_64430714',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f1bc08581_64430714')) {function content_50817f1bc08581_64430714($_smarty_tpl) {?>



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