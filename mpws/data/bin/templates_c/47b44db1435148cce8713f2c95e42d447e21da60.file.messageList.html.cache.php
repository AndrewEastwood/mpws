<?php /* Smarty version Smarty-3.1.11, created on 2012-10-24 10:15:57
         compiled from "/var/www/mpws/web/default/v1.0/template/component/messageList.html" */ ?>
<?php /*%%SmartyHeaderCode:1628427022508156b298e854-33391778%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47b44db1435148cce8713f2c95e42d447e21da60' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/messageList.html',
      1 => 1351062951,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1628427022508156b298e854-33391778',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156b2a0a4f3_51595995',
  'variables' => 
  array (
    '_ownerName' => 0,
    'CURRENT' => 0,
    '_controlOwner' => 0,
    '_messages' => 0,
    '_resourceOwner' => 0,
    'itemvar' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156b2a0a4f3_51595995')) {function content_508156b2a0a4f3_51595995($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSComponent MPWSComponentMessageList MPWSComponentMessageList<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_ownerName']->value);?>
">
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>"MessageList".((string)(($tmp = @$_smarty_tpl->tpl_vars['_controlOwner']->value)===null||$tmp==='' ? '' : $tmp))), 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_messages']->value)){?>
    <ul class="MPWSList">
    <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_messages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
        <li class="MPWSListItem"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)(($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'message' : $tmp))."_message".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['itemvar']->value))};?>
</li>
    <?php } ?>
    </ul>
<?php }?>
</div><?php }} ?>