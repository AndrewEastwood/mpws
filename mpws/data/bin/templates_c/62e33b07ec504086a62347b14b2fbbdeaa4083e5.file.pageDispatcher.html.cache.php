<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:47:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageDispatcher.html" */ ?>
<?php /*%%SmartyHeaderCode:8844791165068afd581be70-37407168%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '62e33b07ec504086a62347b14b2fbbdeaa4083e5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageDispatcher.html',
      1 => 1349021707,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8844791165068afd581be70-37407168',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'SITE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068afd582b479_00574932',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd582b479_00574932')) {function content_5068afd582b479_00574932($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->{"objectTemplatePath_page_".((string)mb_strtolower($_smarty_tpl->tpl_vars['INFO']->value['PAGE'], 'UTF-8'))}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>