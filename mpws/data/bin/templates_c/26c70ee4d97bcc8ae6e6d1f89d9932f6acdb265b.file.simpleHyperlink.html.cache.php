<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 20:55:07
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHyperlink.html" */ ?>
<?php /*%%SmartyHeaderCode:981489855080427bf39687-41928581%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '26c70ee4d97bcc8ae6e6d1f89d9932f6acdb265b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1350327225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '981489855080427bf39687-41928581',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_target' => 0,
    '_attr' => 0,
    '_href' => 0,
    '_link_target' => 0,
    '_title' => 0,
    '_link_attributes' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5080427c02e287_98678023',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5080427c02e287_98678023')) {function content_5080427c02e287_98678023($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_link_target'] = new Smarty_variable('_self', null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['_target']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_link_target'] = new Smarty_variable($_smarty_tpl->tpl_vars['_target']->value, null, 0);?>
<?php }?>
<?php $_smarty_tpl->tpl_vars['_link_attributes'] = new Smarty_variable('', null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['_attr']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_link_attributes'] = new Smarty_variable(" ".((string)$_smarty_tpl->tpl_vars['_attr']->value), null, 0);?>
<?php }?>

<a href="<?php echo $_smarty_tpl->tpl_vars['_href']->value;?>
" target="<?php echo $_smarty_tpl->tpl_vars['_link_target']->value;?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
"<?php echo $_smarty_tpl->tpl_vars['_link_attributes']->value;?>
><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a><?php }} ?>