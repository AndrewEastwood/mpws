<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:40:56
         compiled from "/var/www/mpws/web/default/v1.0/template/macro/widgetGrabber.html" */ ?>
<?php /*%%SmartyHeaderCode:1275548452508158681f9975-40225420%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1122dd0251109bf43aab7e925ecd1cfcf82b8eed' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/macro/widgetGrabber.html',
      1 => 1350627484,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1275548452508158681f9975-40225420',
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
  'unifunc' => 'content_5081586825c384_09113203',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5081586825c384_09113203')) {function content_5081586825c384_09113203($_smarty_tpl) {?>



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