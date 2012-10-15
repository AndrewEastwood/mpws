<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:54
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleHyperlink.html" */ ?>
<?php /*%%SmartyHeaderCode:217127261507ba5e2cc1116-71384274%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa3ac31b0451d94d3da0caded88fd2ba177c2310' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '217127261507ba5e2cc1116-71384274',
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
  'unifunc' => 'content_507ba5e2d49ed3_35025832',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e2d49ed3_35025832')) {function content_507ba5e2d49ed3_35025832($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_link_target'] = new Smarty_variable('_self', null, 0);?>
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