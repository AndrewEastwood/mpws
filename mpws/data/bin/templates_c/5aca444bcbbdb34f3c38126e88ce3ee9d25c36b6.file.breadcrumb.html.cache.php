<?php /* Smarty version Smarty-3.1.11, created on 2012-10-22 23:09:01
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/component/breadcrumb.html" */ ?>
<?php /*%%SmartyHeaderCode:54440418750805fadea6c16-75253972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5aca444bcbbdb34f3c38126e88ce3ee9d25c36b6' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/component/breadcrumb.html',
      1 => 1350936538,
      2 => 'file',
    ),
    'ff723190029025c7eca9b1a09f9c94bdce3626f6' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/breadcrumb.html',
      1 => 1350579434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54440418750805fadea6c16-75253972',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50805fadec7773_13027400',
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50805fadec7773_13027400')) {function content_50805fadec7773_13027400($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
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

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_link, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>"?".((string)$_smarty_tpl->tpl_vars['_action']->value),'_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_display_menuText".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY']))}), 0);?>

    <?php }?>

</div><?php }} ?>