<?php /* Smarty version Smarty-3.1.11, created on 2012-10-17 13:26:55
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleTextLabel.html" */ ?>
<?php /*%%SmartyHeaderCode:2132527607507e87ef234a04-38877697%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '325bd79671b49b6c44446e1190742bdc7da32cab' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleTextLabel.html',
      1 => 1350366116,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2132527607507e87ef234a04-38877697',
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
  'unifunc' => 'content_507e87ef29ce27_81524151',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507e87ef29ce27_81524151')) {function content_507e87ef29ce27_81524151($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><span class="MPWSComponent MPWSComponentTextLabel">
    <?php if (!isset($_smarty_tpl->tpl_vars['_resource']->value)){?>
        <?php $_smarty_tpl->tpl_vars['_resource'] = new Smarty_variable('display', null, 0);?>
    <?php }?>
    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value)."TextLabel".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_key']->value))};?>
    
</span>
<?php }} ?>