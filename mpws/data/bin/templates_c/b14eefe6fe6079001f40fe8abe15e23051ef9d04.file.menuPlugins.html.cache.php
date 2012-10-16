<?php /* Smarty version Smarty-3.1.11, created on 2012-10-16 09:18:45
         compiled from "/var/www/mpws/web/default/v1.0/template/component/menuPlugins.html" */ ?>
<?php /*%%SmartyHeaderCode:1462771496507ba5e2e77fc5-19334826%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b14eefe6fe6079001f40fe8abe15e23051ef9d04' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/menuPlugins.html',
      1 => 1350368313,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1462771496507ba5e2e77fc5-19334826',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5e2eb3524_93533018',
  'variables' => 
  array (
    'OBJECT' => 0,
    'webObj' => 0,
    'submenu' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e2eb3524_93533018')) {function content_507ba5e2eb3524_93533018($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenMenu MPWSComponenMenuPlugins">
    <ul class="MPWSList">
    <?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['OBJECT']->value['WOB']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
        <li class="MPWSListItem">
            <a href="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_object_menuExecObjectPath;?>
?plugin=<?php echo $_smarty_tpl->tpl_vars['webObj']->value->getObjectName();?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
">
                <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
</span>
            </a>
            <?php $_smarty_tpl->tpl_vars['submenu'] = new Smarty_variable($_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_display_menuPlugin, null, 0);?>
            <?php if (!empty($_smarty_tpl->tpl_vars['submenu']->value)){?>
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['submenu']->value,'_OBJ'=>$_smarty_tpl->tpl_vars['webObj']->value), 0);?>

            <?php }?>
        </li>
    <?php } ?>
    </ul>
</div><?php }} ?>