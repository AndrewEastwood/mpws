<?php /* Smarty version Smarty-3.1.11, created on 2012-10-24 08:42:51
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/systemUserInfo.html" */ ?>
<?php /*%%SmartyHeaderCode:1298954885508154ef275a95-45071070%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '929cbd1ddf297f9edb80d79f7d4b55ebae01ad54' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1351057045,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1298954885508154ef275a95-45071070',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508154ef2d3457_99741608',
  'variables' => 
  array (
    'CURRENT' => 0,
    '_widgetName' => 0,
    'INFO' => 0,
    'simpleHyperlinkHomepage' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508154ef2d3457_99741608')) {function content_508154ef2d3457_99741608($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("SystemUserInfo", null, 0);?>
<div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        <div class="MPWSFormHeader">
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>$_smarty_tpl->tpl_vars['_widgetName']->value), 0);?>

        </div>
        <div class="MPWSFormBody">
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoWelcomeMessage,'_value'=>$_smarty_tpl->tpl_vars['INFO']->value['USER']['NAME']), 0);?>

        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoLastAccess,'_value'=>$_smarty_tpl->tpl_vars['INFO']->value['USER']['SINCE']), 0);?>

        
        <?php $_smarty_tpl->tpl_vars['simpleHyperlinkHomepage'] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_link, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage,'_title'=>@MPWS_CUSTOMER), 0));?>

        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoHomePageLinkPrefix,'_value'=>$_smarty_tpl->tpl_vars['simpleHyperlinkHomepage']->value), 0);?>

        
        </div>
        <div class="MPWSFormFooter">
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('SignOut'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value), 0);?>

        </div>
    </form>
</div><?php }} ?>