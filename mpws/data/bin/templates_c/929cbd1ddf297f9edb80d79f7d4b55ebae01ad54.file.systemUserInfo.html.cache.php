<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:54
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/systemUserInfo.html" */ ?>
<?php /*%%SmartyHeaderCode:427127429507ba5e2b686d9-16335561%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '929cbd1ddf297f9edb80d79f7d4b55ebae01ad54' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '427127429507ba5e2b686d9-16335561',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    'INFO' => 0,
    'simpleHyperlinkHomepage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5e2bd8f86_44857205',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e2bd8f86_44857205')) {function content_507ba5e2bd8f86_44857205($_smarty_tpl) {?><div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_divRowLabelValue, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoWelcomeMessage,'_value'=>$_smarty_tpl->tpl_vars['INFO']->value['USER']['NAME']), 0);?>

        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_divRowLabelValue, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoLastAccess,'_value'=>$_smarty_tpl->tpl_vars['INFO']->value['USER']['SINCE']), 0);?>

        
        <?php $_smarty_tpl->tpl_vars['simpleHyperlinkHomepage'] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleHyperlink, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectConfiguration_customer_homepage,'_title'=>@MPWS_CUSTOMER), 0));?>

        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_divRowLabelValue, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoHomePageLinkPrefix,'_value'=>$_smarty_tpl->tpl_vars['simpleHyperlinkHomepage']->value), 0);?>

        <button type="submit" name="do" value="logout" class="MPWSButton"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_systemUserInfoSignoutButtonText;?>
</button>
    </form>
</div><?php }} ?>