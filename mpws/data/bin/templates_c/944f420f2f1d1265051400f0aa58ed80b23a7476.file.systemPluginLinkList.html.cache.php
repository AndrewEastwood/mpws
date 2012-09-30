<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:50:50
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkList.html" */ ?>
<?php /*%%SmartyHeaderCode:3851944085068b0aae96395-65691081%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '944f420f2f1d1265051400f0aa58ed80b23a7476' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkList.html',
      1 => 1349022615,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3851944085068b0aae96395-65691081',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'WOB' => 0,
    'webObj' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068b0aaeeee64_87145651',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068b0aaeeee64_87145651')) {function content_5068b0aaeeee64_87145651($_smarty_tpl) {?><div id="MPWSWidgetSystemPluginLinkListID" class="MPWSWidget MPWSWidgetSystemPluginLinkList">
    <ul class="MPWSList MPWSListPluginLinks">
    <?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['WOB']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
        <li class="MPWSListItem MPWSListItemPluginLink">
            <a href="?plugin=<?php echo $_smarty_tpl->tpl_vars['webObj']->value->getObjectName();?>
" title="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
">
                <div class="MPWSMiniBlock MPWSMiniBlockSurveys" id="MPWSMiniBlockSurveysID">
                    <div class="MPWSWrapper">
                        <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
</span>
                        <span class="MPWSText MPWSTextAction"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListDescription;?>
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