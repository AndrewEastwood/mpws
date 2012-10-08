<?php /* Smarty version Smarty-3.1.11, created on 2012-10-09 00:12:46
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkList.html" */ ?>
<?php /*%%SmartyHeaderCode:1227570134507341cea0d8a2-38753363%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '944f420f2f1d1265051400f0aa58ed80b23a7476' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkList.html',
      1 => 1349375490,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1227570134507341cea0d8a2-38753363',
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
  'unifunc' => 'content_507341cea5d428_75865322',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507341cea5d428_75865322')) {function content_507341cea5d428_75865322($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginLinkListID" class="MPWSWidget MPWSWidgetSystemPluginLinkList">
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