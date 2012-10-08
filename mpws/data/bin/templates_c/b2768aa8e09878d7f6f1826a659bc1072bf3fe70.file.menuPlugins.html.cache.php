<?php /* Smarty version Smarty-3.1.11, created on 2012-10-09 00:11:27
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menuPlugins.html" */ ?>
<?php /*%%SmartyHeaderCode:16783467875073417fed4ab7-86143869%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b2768aa8e09878d7f6f1826a659bc1072bf3fe70' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menuPlugins.html',
      1 => 1349290726,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16783467875073417fed4ab7-86143869',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'OBJECT' => 0,
    'webObj' => 0,
    'submenu' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5073417ff07934_42972055',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5073417ff07934_42972055')) {function content_5073417ff07934_42972055($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenMenuPlugins">
    <ul class="MPWSList MPWSListPluginLinks">
    <?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['OBJECT']->value['WOB']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
        <li class="MPWSListItem MPWSListItemPluginLink">
            <a href="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_object_menuExecObjectPath;?>
?plugin=<?php echo $_smarty_tpl->tpl_vars['webObj']->value->getObjectName();?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
">
                <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
</span>
                <?php $_smarty_tpl->tpl_vars['submenu'] = new Smarty_variable($_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_display_menuPlugin, null, 0);?>
                <?php if (!empty($_smarty_tpl->tpl_vars['submenu']->value)){?>
                    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['submenu']->value,'_OBJ'=>$_smarty_tpl->tpl_vars['webObj']->value), 0);?>

                <?php }?>
            </a>
        </li>
    <?php } ?>
    </ul>
</div><?php }} ?>