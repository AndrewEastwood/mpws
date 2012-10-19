<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:59
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/messageList.html" */ ?>
<?php /*%%SmartyHeaderCode:1332716550817f171be698-71063015%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1d9bc5d7a3d04d87c716664e439f92529a65f6f2' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/messageList.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1332716550817f171be698-71063015',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_ownerName' => 0,
    'CURRENT' => 0,
    '_messages' => 0,
    'itemvar' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50817f17208002_03294396',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f17208002_03294396')) {function content_50817f17208002_03294396($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSComponent MPWSComponentMessageList MPWSComponentMessageList<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_ownerName']->value);?>
">
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>"MessageList".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_ownerName']->value))), 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_messages']->value)){?>
    <ul class="MPWSList">
    <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_messages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
        <li class="MPWSListItem"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_message_".((string)$_smarty_tpl->tpl_vars['itemvar']->value)};?>
</li>
    <?php } ?>
    </ul>
<?php }?>
</div><?php }} ?>