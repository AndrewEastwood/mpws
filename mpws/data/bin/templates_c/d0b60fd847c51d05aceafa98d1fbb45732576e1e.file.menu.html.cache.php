<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:47:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html" */ ?>
<?php /*%%SmartyHeaderCode:14935958825068afd59a1079-05977024%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0b60fd847c51d05aceafa98d1fbb45732576e1e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html',
      1 => 1348769917,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14935958825068afd59a1079-05977024',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_items' => 0,
    'itemvar' => 0,
    'keyvar' => 0,
    'SITE' => 0,
    '_custom' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068afd59e7835_86213804',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd59e7835_86213804')) {function content_5068afd59e7835_86213804($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSComponent MPWSComponenMenu">
    <?php if (isset($_smarty_tpl->tpl_vars['_items']->value)){?>
    <ul class="MPWSList MPWSListMenu">
    <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
        <li class="MPWSListItem MPWSListItemMenu">
            <a href="<?php echo $_smarty_tpl->tpl_vars['itemvar']->value['link'];?>
" target="<?php echo $_smarty_tpl->tpl_vars['itemvar']->value['target'];?>
" title=""><?php echo $_smarty_tpl->tpl_vars['SITE']->value->{"objectProperty_display_menuText".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['keyvar']->value,0,1))};?>
</a>
        </li>
    <?php } ?>
    <?php if (isset($_smarty_tpl->tpl_vars['_custom']->value)){?>
    <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_custom']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
        <li class="MPWSListItem MPWSListItemMenu">
            <a href="<?php echo $_smarty_tpl->tpl_vars['itemvar']->value['link'];?>
" target="<?php echo $_smarty_tpl->tpl_vars['itemvar']->value['target'];?>
" title=""><?php echo $_smarty_tpl->tpl_vars['SITE']->value->{"objectProperty_display_menuText".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['keyvar']->value,0,1))};?>
</a>
        </li>
    <?php } ?>
    <?php }?>
    </ul>
    <?php }?>
</div><?php }} ?>