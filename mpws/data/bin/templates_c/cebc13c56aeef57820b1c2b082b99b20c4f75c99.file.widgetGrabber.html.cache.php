<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:54
         compiled from "/var/www/mpws/web/default/v1.0/template/component/widgetGrabber.html" */ ?>
<?php /*%%SmartyHeaderCode:1826633666507ba5e2a05832-98159891%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cebc13c56aeef57820b1c2b082b99b20c4f75c99' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/widgetGrabber.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1826633666507ba5e2a05832-98159891',
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
  'unifunc' => 'content_507ba5e2a685f4_30196117',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e2a685f4_30196117')) {function content_507ba5e2a685f4_30196117($_smarty_tpl) {?>



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