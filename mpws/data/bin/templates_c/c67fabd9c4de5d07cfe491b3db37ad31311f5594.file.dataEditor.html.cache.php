<?php /* Smarty version Smarty-3.1.11, created on 2012-10-10 22:47:01
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html" */ ?>
<?php /*%%SmartyHeaderCode:17465868775075c74a3a5e50-31766936%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c67fabd9c4de5d07cfe491b3db37ad31311f5594' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1349898414,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17465868775075c74a3a5e50-31766936',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5075c74a3f0570_27203110',
  'variables' => 
  array (
    'CURRENT' => 0,
    'fieldEntry' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5075c74a3f0570_27203110')) {function content_5075c74a3f0570_27203110($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_dataEditor".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'])}, null, 0);?>

<div id="MPWSWidget<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'];?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'];?>
">
    
    <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
    
        <h2><?php echo $_smarty_tpl->tpl_vars['fieldEntry']->value['Type'];?>
</h2>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_databaseField, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field']), 0);?>

    <?php } ?>
    
</div><?php }} ?>