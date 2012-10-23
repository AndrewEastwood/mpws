<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 21:04:50
         compiled from "/var/www/mpws/web/default/v1.0/template/control/mpwsLinkAction.html" */ ?>
<?php /*%%SmartyHeaderCode:12523122855084e5c8ac8b05-55185386%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd02973ef8e80aa05e187fe40f5356d8e16a2e28e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1351015448,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12523122855084e5c8ac8b05-55185386',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5084e5c8be4990_40474378',
  'variables' => 
  array (
    '_href' => 0,
    '_target' => 0,
    '_resourceOwner' => 0,
    '_controlOwner' => 0,
    '_action' => 0,
    'CURRENT' => 0,
    'controlLinkHref' => 0,
    '_mode' => 0,
    '_oid' => 0,
    '_attributes' => 0,
    '_linkActions' => 0,
    '_linkAction' => 0,
    'INFO' => 0,
    'controlLinkTarget' => 0,
    'controlLinkAttr' => 0,
    'controlLinkTitle' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5084e5c8be4990_40474378')) {function content_5084e5c8be4990_40474378($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars['controlLinkHref'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_href']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['controlLinkTarget'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_target']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'control' : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars['controlLinkTitle'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_controlActionLinkTitle".((string)$_smarty_tpl->tpl_vars['_controlOwner']->value).((string)$_smarty_tpl->tpl_vars['_action']->value)}, null, 0);?>

<?php $_smarty_tpl->tpl_vars['controlLinkAttr'] = new Smarty_variable(array(), null, 0);?>

<?php if (empty($_smarty_tpl->tpl_vars['controlLinkHref']->value)){?>
    <?php if (!isset($_smarty_tpl->tpl_vars['_mode']->value)||$_smarty_tpl->tpl_vars['_mode']->value=='normal'){?>

        <?php $_smarty_tpl->tpl_vars['_linkActions'] = new Smarty_variable(array(), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_attributes'] = new Smarty_variable(array(), null, 0);?>
        <?php if (empty($_smarty_tpl->tpl_vars['controlLinkHref']->value)){?>
            <?php $_smarty_tpl->tpl_vars['controlLinkHref'] = new Smarty_variable('#', null, 0);?>
        <?php }?>

        <?php if (isset($_smarty_tpl->tpl_vars['_action']->value)){?>
            <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "action=".((string)mb_strtolower($_smarty_tpl->tpl_vars['_action']->value, 'UTF-8'));?>
            <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "mpws-action=\"".((string)$_smarty_tpl->tpl_vars['_action']->value)."\"";?>
        <?php }?>
        <?php if (!empty($_smarty_tpl->tpl_vars['_oid']->value)){?>
            <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "oid=".((string)$_smarty_tpl->tpl_vars['_oid']->value);?>
            <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "mpws-oid=\"".((string)$_smarty_tpl->tpl_vars['_oid']->value)."\"";?>
        <?php }?>

        <?php $_smarty_tpl->tpl_vars['controlLinkAttr'] = new Smarty_variable(implode(' ',$_smarty_tpl->tpl_vars['_attributes']->value), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable(implode('&amp;',$_smarty_tpl->tpl_vars['_linkActions']->value), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['controlLinkHref'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['controlLinkHref']->value)."?".((string)$_smarty_tpl->tpl_vars['_linkAction']->value), null, 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_mode']->value=='system'){?>

        
        <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "plugin=".((string)mb_strtolower($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->getObjectName(), 'UTF-8'));?>
        <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "display=".((string)mb_strtolower($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'], 'UTF-8'));?>
        <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "action=".((string)mb_strtolower($_smarty_tpl->tpl_vars['_action']->value, 'UTF-8'));?>

        
        <?php if (!empty($_smarty_tpl->tpl_vars['_oid']->value)){?>
            <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = "oid=".((string)$_smarty_tpl->tpl_vars['_oid']->value);?>
            <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "mpws-oid=\"".((string)$_smarty_tpl->tpl_vars['_oid']->value)."\"";?>
        <?php }?>

        
        <?php $_smarty_tpl->tpl_vars['_linkAction'] = new Smarty_variable(implode('&amp;',$_smarty_tpl->tpl_vars['_linkActions']->value), null, 0);?>

        
        <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "mpws-action=\"".((string)$_smarty_tpl->tpl_vars['_action']->value)."\"";?>
        <?php if (!empty($_smarty_tpl->tpl_vars['controlLinkTarget']->value)){?>
            <?php $_smarty_tpl->createLocalArrayVariable('_attributes', null, 0);
$_smarty_tpl->tpl_vars['_attributes']->value[] = "target=\"".((string)$_smarty_tpl->tpl_vars['controlLinkTarget']->value)."\"";?>
        <?php }?>

        
        <?php $_smarty_tpl->tpl_vars['controlLinkAttr'] = new Smarty_variable(implode(' ',$_smarty_tpl->tpl_vars['_attributes']->value), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['controlLinkHref'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_object_menuExecObjectPath)."?".((string)$_smarty_tpl->tpl_vars['_linkAction']->value), null, 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Wrong action link mode",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

    <?php }?>
<?php }?>


<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_link, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>$_smarty_tpl->tpl_vars['controlLinkHref']->value,'_target'=>$_smarty_tpl->tpl_vars['controlLinkTarget']->value,'_attr'=>$_smarty_tpl->tpl_vars['controlLinkAttr']->value,'_title'=>$_smarty_tpl->tpl_vars['controlLinkTitle']->value), 0);?>
<?php }} ?>