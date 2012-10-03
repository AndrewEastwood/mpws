<?php /* Smarty version Smarty-3.1.11, created on 2012-10-04 00:01:18
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html" */ ?>
<?php /*%%SmartyHeaderCode:1378041022506ca5b4285e59-87316228%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89315d90c92cff95f5c3b5148e051f8e4fa0af29' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html',
      1 => 1349298075,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1378041022506ca5b4285e59-87316228',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506ca5b42ec3d7_32116027',
  'variables' => 
  array (
    '_action' => 0,
    '_resource' => 0,
    '_name' => 0,
    'CURRENT' => 0,
    '_linkAction' => 0,
    '_linkText' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506ca5b42ec3d7_32116027')) {function content_506ca5b42ec3d7_32116027($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php if (isset($_smarty_tpl->tpl_vars['_action']->value)){?>

<?php if ($_smarty_tpl->tpl_vars['_action']->value=='edit'){?>
	<?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable('?action=edit&amp;oid=XXX', null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['_action']->value=='view'){?>
	<?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable('?action=view&amp;oid=XXX', null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['_action']->value=='delete'){?>
	<?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable('?action=delete&amp;oid=XXX', null, 0);?>
<?php }else{ ?>
	<?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable('?action=default&amp;oid=XXX', null, 0);?>
<?php }?>

<?php $_smarty_tpl->tpl_vars['_linkText'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_name']->value).((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_action']->value,0,1))}, null, 0);?>
<a href="<?php echo $_smarty_tpl->tpl_vars['_linkAction']->value;?>
" class="MPWSLink" target="" mpws-action="<?php echo $_smarty_tpl->tpl_vars['_action']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>
">
	<?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>

</a>
<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Action name is empty or not provided",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?><?php }} ?>