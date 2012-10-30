<?php /* Smarty version Smarty-3.1.11, created on 2012-10-30 22:48:09
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsLinkAction.html" */ ?>
<?php /*%%SmartyHeaderCode:19152304395081a14daa68a7-90403107%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f41ac3515949000dfb743e53eb00b074a6cc20f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1351630087,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19152304395081a14daa68a7-90403107',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5081a14dbb9a35_87469309',
  'variables' => 
  array (
    '_href' => 0,
    '_target' => 0,
    '_resourceOwner' => 0,
    '_useValueAsTitle' => 0,
    '_controlOwner' => 0,
    '_action' => 0,
    'CURRENT' => 0,
    '_customParams' => 0,
    'controlLinkHref' => 0,
    '_mode' => 0,
    '_oid' => 0,
    '_attributes' => 0,
    '_linkActions' => 0,
    '_linkAction' => 0,
    'INFO' => 0,
    'customParams' => 0,
    '_p' => 0,
    '_pk' => 0,
    'controlLinkTarget' => 0,
    'controlLinkAttr' => 0,
    'controlLinkTitle' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5081a14dbb9a35_87469309')) {function content_5081a14dbb9a35_87469309($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars['controlLinkHref'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_href']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['controlLinkTarget'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_target']->value)===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'control' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['controlLinkTitle'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_useValueAsTitle']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_controlActionLinkTitle".((string)$_smarty_tpl->tpl_vars['_controlOwner']->value).((string)mb_strtoupper($_smarty_tpl->tpl_vars['_action']->value, 'UTF-8'))} : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['customParams'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_customParams']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars['controlLinkAttr'] = new Smarty_variable(array("id=\"MPWSActionLink_".((string)$_smarty_tpl->tpl_vars['_action']->value)."ID\""), null, 0);?>

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

        
        <?php if (!empty($_smarty_tpl->tpl_vars['customParams']->value)){?>
            <?php  $_smarty_tpl->tpl_vars['_pk'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_pk']->_loop = false;
 $_smarty_tpl->tpl_vars['_p'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['customParams']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_pk']->key => $_smarty_tpl->tpl_vars['_pk']->value){
$_smarty_tpl->tpl_vars['_pk']->_loop = true;
 $_smarty_tpl->tpl_vars['_p']->value = $_smarty_tpl->tpl_vars['_pk']->key;
?>
                <?php $_smarty_tpl->createLocalArrayVariable('_linkActions', null, 0);
$_smarty_tpl->tpl_vars['_linkActions']->value[] = ((string)$_smarty_tpl->tpl_vars['_p']->value)."=".((string)$_smarty_tpl->tpl_vars['_pk']->value);?>
            <?php } ?>
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