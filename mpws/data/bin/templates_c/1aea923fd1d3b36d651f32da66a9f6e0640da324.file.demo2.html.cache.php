<?php /* Smarty version Smarty-3.1.11, created on 2012-09-27 22:43:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/demo2.html" */ ?>
<?php /*%%SmartyHeaderCode:11665293025064ac55369c46-56888154%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1aea923fd1d3b36d651f32da66a9f6e0640da324' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/demo2.html',
      1 => 1348177708,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11665293025064ac55369c46-56888154',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE' => 0,
    'wgt_demo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5064ac55382d56_62192047',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5064ac55382d56_62192047')) {function content_5064ac55382d56_62192047($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['wgt_demo'] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_widget_demo, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0));?>

DEFAULT DEMO 2<br>
<?php echo $_smarty_tpl->tpl_vars['wgt_demo']->value;?>
<br>
Property test = <?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectProperty_demo_myprop;?>


<br><br><br><?php }} ?>