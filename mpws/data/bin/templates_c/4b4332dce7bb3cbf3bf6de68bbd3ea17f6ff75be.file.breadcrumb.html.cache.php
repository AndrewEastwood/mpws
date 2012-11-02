<?php /* Smarty version Smarty-3.1.11, created on 2012-10-24 19:18:02
         compiled from "/var/www/mpws/web/customer/toolbox/template/component/breadcrumb.html" */ ?>
<?php /*%%SmartyHeaderCode:1596175720508156d1eab475-02699441%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b4332dce7bb3cbf3bf6de68bbd3ea17f6ff75be' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/component/breadcrumb.html',
      1 => 1351095480,
      2 => 'file',
    ),
    '240c0ec7052d952b311c2c1e10414aadecb967f7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/breadcrumb.html',
      1 => 1350563538,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1596175720508156d1eab475-02699441',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d2040bb5_10599186',
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d2040bb5_10599186')) {function content_508156d2040bb5_10599186($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSComponent MPWSComponentBreadcrumb">
    
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