<?php /* Smarty version Smarty-3.1.11, created on 2012-10-10 22:47:02
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/databaseField.html" */ ?>
<?php /*%%SmartyHeaderCode:7109522595075d0b6058698-85839637%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58d5b137345a5af07bb619330436dbfb288a7788' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/databaseField.html',
      1 => 1349898418,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7109522595075d0b6058698-85839637',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_type' => 0,
    'CURRENT' => 0,
    '_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5075d0b60b6709_83876787',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5075d0b60b6709_83876787')) {function content_5075d0b60b6709_83876787($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['_type']->value=='text'){?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value), 0);?>

<?php }?><?php }} ?>