<?php /* Smarty version Smarty-3.1.11, created on 2012-10-29 09:20:30
         compiled from "/var/www/mpws/web/default/v1.0/template/simple/link.html" */ ?>
<?php /*%%SmartyHeaderCode:496418734508155abb2cdf7-76683408%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f53137f2b7fe9fb4a8d2f0e039ef190f3c30373d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/link.html',
      1 => 1351493136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '496418734508155abb2cdf7-76683408',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508155abb6e3b4_48711624',
  'variables' => 
  array (
    '_target' => 0,
    '_attr' => 0,
    '_href' => 0,
    '_title' => 0,
    '_link_target' => 0,
    'attr' => 0,
    '_link_attributes' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508155abb6e3b4_48711624')) {function content_508155abb6e3b4_48711624($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_link_target'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_target']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_link_attributes'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_attr']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars['attr'] = new Smarty_variable(array("href=\"".((string)$_smarty_tpl->tpl_vars['_href']->value)."\"","title=\"".((string)$_smarty_tpl->tpl_vars['_title']->value)."\""), null, 0);?>
<?php if (!empty($_smarty_tpl->tpl_vars['_link_target']->value)){?>
    <?php $_smarty_tpl->createLocalArrayVariable('attr', null, 0);
$_smarty_tpl->tpl_vars['attr']->value[] = array("target=\"".((string)$_smarty_tpl->tpl_vars['_link_target']->value)."\"");?>
<?php }?>

<a <?php echo implode(' ',$_smarty_tpl->tpl_vars['attr']->value);?>
  class="MPWSLink" <?php echo implode(' ',$_smarty_tpl->tpl_vars['_link_attributes']->value);?>
><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a><?php }} ?>