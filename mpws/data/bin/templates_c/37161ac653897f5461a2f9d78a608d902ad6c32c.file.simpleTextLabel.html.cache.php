<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 21:03:55
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleTextLabel.html" */ ?>
<?php /*%%SmartyHeaderCode:5559414575080448b8ec1c4-88779761%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '37161ac653897f5461a2f9d78a608d902ad6c32c' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleTextLabel.html',
      1 => 1350334745,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5559414575080448b8ec1c4-88779761',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_resource' => 0,
    '_ownerName' => 0,
    '_key' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5080448b946f82_89629279',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5080448b946f82_89629279')) {function content_5080448b946f82_89629279($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><span class="MPWSComponent MPWSComponentTextLabel">
    <?php if (!isset($_smarty_tpl->tpl_vars['_resource']->value)){?>
        <?php $_smarty_tpl->tpl_vars['_resource'] = new Smarty_variable('display', null, 0);?>
    <?php }?>
    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value)."TextLabel".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
    
</span>
<?php }} ?>