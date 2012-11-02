<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:26:05
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginList.html" */ ?>
<?php /*%%SmartyHeaderCode:48102315750804285a89a05-21229726%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c04927cecebe8ade47c54256c5471962aff6234b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginList.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48102315750804285a89a05-21229726',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50804285af0508_00497324',
  'variables' => 
  array (
    'CURRENT' => 0,
    'OBJECT' => 0,
    'webObj' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50804285af0508_00497324')) {function content_50804285af0508_00497324($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div id="MPWSWidgetSystemPluginListID" class="MPWSWidget MPWSWidgetSystemPluginList">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>'SystemPluginList'), 0);?>

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