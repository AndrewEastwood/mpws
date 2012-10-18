<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 12:20:16
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/systemPluginList.html" */ ?>
<?php /*%%SmartyHeaderCode:806051908507fa391cfa316-67590663%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f90a9199a80b6449d3c31c883efdec6c8d75f9ee' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/systemPluginList.html',
      1 => 1350551945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '806051908507fa391cfa316-67590663',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507fa391d52385_45758148',
  'variables' => 
  array (
    'OBJECT' => 0,
    'webObj' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507fa391d52385_45758148')) {function content_507fa391d52385_45758148($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div id="MPWSWidgetSystemPluginListID" class="MPWSWidget MPWSWidgetSystemPluginList">
    <ul class="MPWSList">
    <?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['OBJECT']->value['WOB']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
        <li id="MPWSListItem<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['webObj']->value->getObjectName(),0,1);?>
ID" class="MPWSListItem MPWSListItem<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['webObj']->value->getObjectName(),0,1);?>
">
            <a href="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_object_menuExecObjectPath;?>
?plugin=<?php echo mb_strtolower($_smarty_tpl->tpl_vars['webObj']->value->getObjectName(), 'UTF-8');?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
">
                <div class="MPWSBlock">
                    <div class="MPWSWrapper">
                        <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
</span>
                        <span class="MPWSText MPWSTextDescription"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListDescription;?>
</span>
                        <span class="MPWSText MPWSTextLink"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginLinkListStartText;?>
</span>
                    </div>
                </div>
            </a>
        </li>
    <?php } ?>
    </ul>
</div><?php }} ?>