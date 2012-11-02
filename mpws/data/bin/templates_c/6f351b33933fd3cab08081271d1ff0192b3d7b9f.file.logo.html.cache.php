<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:34:10
         compiled from "/var/www/mpws/web/default/v1.0/template/component/logo.html" */ ?>
<?php /*%%SmartyHeaderCode:1934798999508156d20cf2d6-02001738%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f351b33933fd3cab08081271d1ff0192b3d7b9f' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/logo.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1934798999508156d20cf2d6-02001738',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d20dcce7_28721495',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d20dcce7_28721495')) {function content_508156d20dcce7_28721495($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentLogo">
    <a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink">
        <img src="/static/<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_display_logoFileName;?>
" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div><?php }} ?>