<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:34:09
         compiled from "/var/www/mpws/web/default/v1.0/template/component/exception.html" */ ?>
<?php /*%%SmartyHeaderCode:650499524508156d1a93236-21293365%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09060adb6c9302790e54efd72387b6f6884c7a0e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/exception.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '650499524508156d1a93236-21293365',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    '_message' => 0,
    '_tpl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d1c41588_05929171',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d1c41588_05929171')) {function content_508156d1c41588_05929171($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.date_format.php';
?><div class="MPWSComponent MPWSComponentException">
	<p class="MPWSExceptionMessage">[<?php echo smarty_modifier_date_format(time(),$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_format_dateTimeOnlyTime);?>
] EXCEPTION <?php echo $_smarty_tpl->tpl_vars['_message']->value;?>
 at <?php echo $_smarty_tpl->tpl_vars['_tpl']->value;?>
;</p>
</div><?php }} ?>