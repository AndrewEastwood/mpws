<?php /* Smarty version Smarty-3.1.11, created on 2012-11-06 10:07:54
         compiled from "/var/www/mpws/web/default/v1.0/template/simple/style.html" */ ?>
<?php /*%%SmartyHeaderCode:12338436595098c55aeefd03-40953174%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8d61e0e2135226c9d3e524263dda3ffc8d30462f' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/style.html',
      1 => 1352188723,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12338436595098c55aeefd03-40953174',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_link' => 0,
    '_source' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5098c55af02df8_63419980',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5098c55af02df8_63419980')) {function content_5098c55af02df8_63419980($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['_link']->value)){?>
<link href="<?php echo $_smarty_tpl->tpl_vars['_link']->value;?>
" type="text/css" rel="stylesheet"/>
<?php }elseif(!empty($_smarty_tpl->tpl_vars['_source']->value)){?>
<style type="text/css" rel="stylesheet"><?php echo $_smarty_tpl->tpl_vars['_source']->value;?>
</style>
<?php }?><?php }} ?>