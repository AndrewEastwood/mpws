<?php /* Smarty version Smarty-3.1.11, created on 2012-10-13 00:45:10
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html" */ ?>
<?php /*%%SmartyHeaderCode:105153097350788f668519c0-42081481%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c67fabd9c4de5d07cfe491b3db37ad31311f5594' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1350078058,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105153097350788f668519c0-42081481',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    '_formInnerAction' => 0,
    '_widgetName' => 0,
    'DTV_CFG' => 0,
    'fieldEntry' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50788f669602b3_03678065',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50788f669602b3_03678065')) {function content_50788f669602b3_03678065($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("dataEditor".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME']), null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_dataEditor".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'])}, null, 0);?>

<div id="MPWSWidget<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'];?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'];?>
">
    
    <?php $_smarty_tpl->tpl_vars['_formInnerAction'] = new Smarty_variable(mb_strtolower(libraryRequest::getPostFormAction(), 'UTF-8'), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['_sourceOID'] = new Smarty_variable(libraryRequest::getOID(), null, 0);?>
    <?php if (empty($_smarty_tpl->tpl_vars['_formInnerAction']->value)){?>
        <?php $_smarty_tpl->tpl_vars['_formInnerAction'] = new Smarty_variable("new", null, 0);?>
    <?php }?>
    
    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_objectSummary, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_ownerName'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_customText'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_component_editBoxState".((string)mb_strtoupper($_smarty_tpl->tpl_vars['_formInnerAction']->value, 'UTF-8'))}), 0);?>

    
    
    <form action="<?php echo $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['action'];?>
" name="data_edit_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'], 'UTF-8');?>
" method="<?php echo $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['method'];?>
" class="MPWSForm MPWSFormEditor MPWSFormEditorPage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_formInnerAction']->value,0,1);?>
">

    
    
    <?php if ($_smarty_tpl->tpl_vars['_formInnerAction']->value=="new"){?>
    
        
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_databaseField, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetName']->value), 0);?>

        <?php } ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'custom','_page'=>$_smarty_tpl->tpl_vars['_formInnerAction']->value,'_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form']['edit']['buttons']), 0);?>

    
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="edit"){?>
    
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_databaseField, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetName']->value), 0);?>

        <?php } ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'custom','_page'=>$_smarty_tpl->tpl_vars['_formInnerAction']->value,'_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form']['edit']['buttons']), 0);?>

        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="preview"){?>
    
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_databaseField, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_readonly'=>true), 0);?>

        <?php } ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'custom','_page'=>$_smarty_tpl->tpl_vars['_formInnerAction']->value,'_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form']['preview']['buttons']), 0);?>

        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="save"){?>
    
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'custom','_page'=>$_smarty_tpl->tpl_vars['_formInnerAction']->value,'_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form']['save']['buttons']), 0);?>

        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="cancel"){?>
    
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'custom','_page'=>$_smarty_tpl->tpl_vars['_formInnerAction']->value,'_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form']['cancel']['buttons']), 0);?>

    <?php }?>

    </form>
    
</div><?php }} ?>