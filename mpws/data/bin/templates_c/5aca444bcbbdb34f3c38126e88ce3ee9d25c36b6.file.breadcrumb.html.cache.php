<?php /* Smarty version Smarty-3.1.11, created on 2013-08-13 23:18:34
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/component/breadcrumb.html" */ ?>
<?php /*%%SmartyHeaderCode:121782273052063414a045c1-91002912%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5aca444bcbbdb34f3c38126e88ce3ee9d25c36b6' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/component/breadcrumb.html',
      1 => 1376425072,
      2 => 'file',
    ),
    'ff723190029025c7eca9b1a09f9c94bdce3626f6' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/breadcrumb.html',
      1 => 1376425056,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '121782273052063414a045c1-91002912',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_52063414ab90f3_55157039',
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52063414ab90f3_55157039')) {function content_52063414ab90f3_55157039($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSComponent MPWSComponentBreadcrumb">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>'BreadcrumbPrefix'), 0);?>

    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'])){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_link, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectConfiguration_object_menuExecObjectPath,'_title'=>$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectProperty_site_title), 0);?>

    <?php }?>

    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])){?>
        <?php $_smarty_tpl->tpl_vars['_action'] = new Smarty_variable(libraryRequest::getNewUrl('plugin',$_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'],1,array('plugin')), null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][mb_strtoupper($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'], 'UTF-8')]->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>'BreadcrumbSeparator'), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_link, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>"?".((string)$_smarty_tpl->tpl_vars['_action']->value),'_title'=>$_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])]->objectProperty_widget_systemPluginListTitle), 0);?>

    <?php }?>

    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'])){?>
        <?php $_smarty_tpl->tpl_vars['_action'] = new Smarty_variable(libraryRequest::getNewUrl('display',$_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'],1,array('plugin','display')), null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>'BreadcrumbSeparator'), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][mb_strtoupper($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'], 'UTF-8')]->objectTemplatePath_simple_link, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>"?".((string)$_smarty_tpl->tpl_vars['_action']->value),'_title'=>$_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][mb_strtoupper($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'], 'UTF-8')]->{"objectProperty_display_menuText".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY']))}), 0);?>

    <?php }?>

</div><?php }} ?>