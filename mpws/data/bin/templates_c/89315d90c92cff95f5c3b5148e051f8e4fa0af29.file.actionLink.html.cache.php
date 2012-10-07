<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 14:59:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html" */ ?>
<?php /*%%SmartyHeaderCode:22331472150716ea24b8421-81502858%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89315d90c92cff95f5c3b5148e051f8e4fa0af29' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html',
      1 => 1349379795,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22331472150716ea24b8421-81502858',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_action' => 0,
    '_resource' => 0,
    '_ownerType' => 0,
    '_ownerName' => 0,
    '__prop__' => 0,
    'CURRENT' => 0,
    'INFO' => 0,
    '_oid' => 0,
    '_linkActions' => 0,
    '_linkAction' => 0,
    '_linkText' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50716ea251ee28_49114420',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50716ea251ee28_49114420')) {function content_50716ea251ee28_49114420($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>


<?php if (isset($_smarty_tpl->tpl_vars['_action']->value)){?>

	
	<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_ownerType']->value).((string)$_smarty_tpl->tpl_vars['_ownerName']->value), null, 0);?>

	<?php $_smarty_tpl->tpl_vars["_linkText"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."ActionLink".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_action']->value,0,1))}, null, 0);?>


	<?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "plugin=".((string)mb_strtolower($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->getObjectName(), 'UTF-8'));?>
	<?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "display=".((string)mb_strtolower($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'], 'UTF-8'));?>
	<?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "action=".((string)mb_strtolower($_smarty_tpl->tpl_vars['_action']->value, 'UTF-8'));?>

	
	<?php if (isset($_smarty_tpl->tpl_vars['_oid']->value)){?>
		<?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "oid=".((string)$_smarty_tpl->tpl_vars['_oid']->value);?>
	<?php }?>

	
	<?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable(implode('&amp;',$_smarty_tpl->tpl_vars['_linkActions']->value), null, 0);?>


	<a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_object_menuExecObjectPath;?>
?<?php echo $_smarty_tpl->tpl_vars['_linkAction']->value;?>
" class="MPWSLink" target="" mpws-action="<?php echo $_smarty_tpl->tpl_vars['_action']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>
">
		<?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>

	</a>

<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Action name is empty or not provided",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?><?php }} ?>