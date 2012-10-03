<?php /* Smarty version Smarty-3.1.11, created on 2012-10-03 23:53:08
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/exception.html" */ ?>
<?php /*%%SmartyHeaderCode:1150016240506ca5b42f9132-64601630%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c08cf8d93488a71731b28b816ce96b240d2b9a50' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/exception.html',
      1 => 1349287120,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1150016240506ca5b42f9132-64601630',
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
  'unifunc' => 'content_506ca5b430ca09_78462174',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506ca5b430ca09_78462174')) {function content_506ca5b430ca09_78462174($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.date_format.php';
?><div class="MPWSComponent MPWSComponentException">
	<p class="MPWSExceptionMessage">[<?php echo smarty_modifier_date_format(time(),$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_format_dateTimeOnlyTime);?>
] EXCEPTION <?php echo $_smarty_tpl->tpl_vars['_message']->value;?>
 at <?php echo $_smarty_tpl->tpl_vars['_tpl']->value;?>
;</p>
</div><?php }} ?>