<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:58
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/trigger/page.html" */ ?>
<?php /*%%SmartyHeaderCode:210430970750817f16e99425-28216813%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ecee82a3674286988a27aeaac3fbd629fc4dcb5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/trigger/page.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '210430970750817f16e99425-28216813',
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
  'unifunc' => 'content_50817f16ef5d42_04047531',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f16ef5d42_04047531')) {function content_50817f16ef5d42_04047531($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_".((string)mb_strtolower($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'], 'UTF-8'))}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>
<?php }} ?>