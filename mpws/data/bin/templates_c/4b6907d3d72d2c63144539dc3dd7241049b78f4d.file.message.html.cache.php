<?php /* Smarty version Smarty-3.1.11, created on 2012-10-13 00:45:05
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/message.html" */ ?>
<?php /*%%SmartyHeaderCode:43089407050788f61098241-75589561%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b6907d3d72d2c63144539dc3dd7241049b78f4d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/message.html',
      1 => 1349287120,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '43089407050788f61098241-75589561',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_realm' => 0,
    'MODEL' => 0,
    'itemvar' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50788f610d3677_10962475',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50788f610d3677_10962475')) {function content_50788f610d3677_10962475($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php if (isset($_smarty_tpl->tpl_vars['_realm']->value)){?>
    <div id="MPWSComponentMessage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_realm']->value,0,1);?>
ID" class="MPWSComponent MPWSComponentMessage MPWSComponentMessage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_realm']->value,0,1);?>
">
    <?php if (isset($_smarty_tpl->tpl_vars['MODEL']->value['MESSAGE'][$_smarty_tpl->tpl_vars['_realm']->value])){?>
        <ul class="MPWSList MPWSListMessages MPWSListMessages<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_realm']->value,0,1);?>
">
        <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MODEL']->value['MESSAGE'][$_smarty_tpl->tpl_vars['_realm']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
            <li class="MPWSListItem MPWSListItemMessage"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_message_".((string)$_smarty_tpl->tpl_vars['itemvar']->value)};?>
</li>
        <?php } ?>
        </ul>
    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Wrong realm type",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

    <?php }?>
    </div>
<?php }?><?php }} ?>