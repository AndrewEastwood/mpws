<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:59
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/link.html" */ ?>
<?php /*%%SmartyHeaderCode:162925418050817f1718e098-99730260%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '535697c6004dc371263dc35eb2c3e9633bf84300' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/link.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '162925418050817f1718e098-99730260',
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
  'unifunc' => 'content_50817f171acaa1_40077741',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f171acaa1_40077741')) {function content_50817f171acaa1_40077741($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_link_target'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_target']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_link_attributes'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_attr']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>

<a href="<?php echo $_smarty_tpl->tpl_vars['_href']->value;?>
" target="<?php echo $_smarty_tpl->tpl_vars['_link_target']->value;?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['_link_attributes']->value;?>
><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a><?php }} ?>