<?php /* Smarty version Smarty-3.1.11, created on 2012-10-11 12:44:23
         compiled from "/var/www/mpws/web/default/v1.0/template/component/pageDispatcher.html" */ ?>
<?php /*%%SmartyHeaderCode:1851187788507694f730abe7-60356892%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9e03eb50762ee8db5b6bfbd19c705caf4faed506' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pageDispatcher.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1851187788507694f730abe7-60356892',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507694f735c319_31555681',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507694f735c319_31555681')) {function content_507694f735c319_31555681($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_".((string)mb_strtolower($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'], 'UTF-8'))}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>