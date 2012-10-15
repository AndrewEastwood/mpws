<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:58
         compiled from "/var/www/mpws/web/default/v1.0/template/component/actionLink.html" */ ?>
<?php /*%%SmartyHeaderCode:1956211823507ba5e6b8d178-25497130%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'da36c658b12a320f0d88d5572808687624b18af9' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/actionLink.html',
      1 => 1350280496,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1956211823507ba5e6b8d178-25497130',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_action' => 0,
    '_ownerName' => 0,
    '_useCustomLink' => 0,
    '__prop__' => 0,
    'CURRENT' => 0,
    '_realm' => 0,
    'INFO' => 0,
    '_oid' => 0,
    '_linkActions' => 0,
    '_linkText' => 0,
    '_target' => 0,
    '_attributes' => 0,
    '_linkAction' => 0,
    '_linkAttr' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5e6cad2f2_29272689',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e6cad2f2_29272689')) {function content_507ba5e6cad2f2_29272689($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>


<?php if (isset($_smarty_tpl->tpl_vars['_action']->value)){?>

	
	<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_custom_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value), null, 0);?>

        <?php if (isset($_smarty_tpl->tpl_vars['_useCustomLink']->value)){?>
            <?php $_smarty_tpl->tpl_vars["_linkText"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."ActionLink".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_action']->value,0,1))}, null, 0);?>
        <?php }else{ ?>
            <?php $_smarty_tpl->tpl_vars["_linkText"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_component_actionLink".((string)$_smarty_tpl->tpl_vars['_realm']->value).((string)mb_strtoupper($_smarty_tpl->tpl_vars['_action']->value, 'UTF-8'))}, null, 0);?>
        <?php }?>


	
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
        
        
        <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "title=\"".((string)$_smarty_tpl->tpl_vars['_linkText']->value)."\"";?>
        <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "mpws-action=\"".((string)$_smarty_tpl->tpl_vars['_action']->value)."\"";?>
        <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "mpws-realm=\"".((string)mb_strtolower($_smarty_tpl->tpl_vars['_realm']->value, 'UTF-8'))."\"";?>
        <?php if (isset($_smarty_tpl->tpl_vars['_target']->value)){?>
            <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "target=\"".((string)$_smarty_tpl->tpl_vars['_target']->value)."\"";?>
        <?php }?>
        
	
	<?php $_smarty_tpl->tpl_vars['_linkAttr'] = new Smarty_variable(implode(' ',$_smarty_tpl->tpl_vars['_attributes']->value), null, 0);?>

	<a href="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_object_menuExecObjectPath;?>
?<?php echo $_smarty_tpl->tpl_vars['_linkAction']->value;?>
" class="MPWSLink" <?php echo $_smarty_tpl->tpl_vars['_linkAttr']->value;?>
 title="<?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>
">
            <?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>

        </a>

<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Action name is empty or not provided",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?><?php }} ?>