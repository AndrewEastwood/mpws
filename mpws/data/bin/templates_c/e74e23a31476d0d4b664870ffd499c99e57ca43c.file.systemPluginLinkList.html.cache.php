<?php /* Smarty version Smarty-3.1.11, created on 2012-10-17 13:23:00
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/systemPluginLinkList.html" */ ?>
<?php /*%%SmartyHeaderCode:410175609507e8704345630-64500961%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e74e23a31476d0d4b664870ffd499c99e57ca43c' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/systemPluginLinkList.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '410175609507e8704345630-64500961',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'OBJECT' => 0,
    'webObj' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507e8704487bf7_02510024',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507e8704487bf7_02510024')) {function content_507e8704487bf7_02510024($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginLinkListID" class="MPWSWidget MPWSWidgetSystemPluginLinkList">
    <ul class="MPWSList MPWSListPluginLinks">
    <?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['OBJECT']->value['WOB']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
        <li class="MPWSListItem MPWSListItemPluginLink">
            <a href="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_object_menuExecObjectPath;?>
?plugin=<?php echo mb_strtolower($_smarty_tpl->tpl_vars['webObj']->value->getObjectName(), 'UTF-8');?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
">
                <div class="MPWSMiniBlock">
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