<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 23:53:46
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/baseDataEditor.html" */ ?>
<?php /*%%SmartyHeaderCode:608632290508703da43d3a8-25164552%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0926debbd5bb20656b79ce866be766dbff646dfe' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/baseDataEditor.html',
      1 => 1351024967,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '608632290508703da43d3a8-25164552',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_widgetName' => 0,
    'CURRENT' => 0,
    '_widgetCustomName' => 0,
    '_formInnerAction' => 0,
    'DTV_CFG' => 0,
    'fieldEntry' => 0,
    '_editFieldRenderMode' => 0,
    '_controlValue' => 0,
    'fieldType' => 0,
    'fieldName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508703da5d7c28_18053679',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508703da5d7c28_18053679')) {function content_508703da5d7c28_18053679($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>

<?php $_smarty_tpl->tpl_vars['_controlOwner'] = new Smarty_variable('DataEditor', null, 0);?>

<?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("dataEditor", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_widgetCustomName'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_widgetName']->value).((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME']), null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_dataEditor".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'])}, null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_widgetName']->value);?>
<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'];?>
ID" class="MPWSWidget MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_widgetName']->value);?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_widgetName']->value);?>
<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'];?>
">
    
    <?php $_smarty_tpl->tpl_vars['_formInnerAction'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['EDIT_PAGE'], null, 0);?>

    
    <?php if ($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ISNEW']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_title, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value,'_customText'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_widget_dataEditorStateNewPage".((string)mb_strtoupper($_smarty_tpl->tpl_vars['_formInnerAction']->value, 'UTF-8'))}), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_title, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value,'_customText'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_widget_dataEditorStateExistedPage".((string)mb_strtoupper($_smarty_tpl->tpl_vars['_formInnerAction']->value, 'UTF-8'))}), 0);?>

    <?php }?>
    
    
    <?php if (!$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['VALID']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_messageList, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_messages'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ERRORS'],'_ownerName'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."Validator"), 0);?>

    <?php }?>
    
    
    <form action="<?php echo $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['action'];?>
" name="data_edit_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'], 'UTF-8');?>
" method="<?php echo $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['method'];?>
" class="MPWSForm MPWSFormEditor MPWSFormEditorPage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_formInnerAction']->value,0,1);?>
">
        <div class="MPWSFormBody">

    
    
    <?php if ($_smarty_tpl->tpl_vars['_formInnerAction']->value=="new"){?>
    
        
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value,'_resource'=>'custom'), 0);?>

        <?php } ?>

    
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="edit"){?>
    
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
        
            <?php $_smarty_tpl->tpl_vars['_editFieldRenderMode'] = new Smarty_variable('normal', null, 0);?>
            <?php if (!empty($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ERRORS'])&&in_array($_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ERRORS'])){?>
                <?php $_smarty_tpl->tpl_vars['_editFieldRenderMode'] = new Smarty_variable('error', null, 0);?>
            <?php }?>
        
            <?php if ($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ISNEW']){?>
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value,'_render'=>$_smarty_tpl->tpl_vars['_editFieldRenderMode']->value,'_resource'=>'custom'), 0);?>

            <?php }else{ ?>
                
                <?php if (!in_array($_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],$_smarty_tpl->tpl_vars['DTV_CFG']->value['fields']['skipIfEditExisted'])){?>
                    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SOURCE'][$_smarty_tpl->tpl_vars['fieldEntry']->value['Field']], null, 0);?>
                    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value,'_render'=>$_smarty_tpl->tpl_vars['_editFieldRenderMode']->value,'_resource'=>'custom'), 0);?>

                <?php }?>
            <?php }?>
            
            
        <?php } ?>

    <?php }?>
        
    <?php if ($_smarty_tpl->tpl_vars['_formInnerAction']->value=="preview"){?>
    
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value,'_renderMode'=>'hidden','_resource'=>'custom'), 0);?>

        <?php } ?>
        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="save"){?>
    
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'widget','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."SaveMain"), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'widget','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."SaveSubText"), 0);?>

        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="cancel"){?>
    
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'widget','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."CancelMain"), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'widget','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."CancelSubText"), 0);?>

        
    <?php }?>
    
    
    <?php  $_smarty_tpl->tpl_vars['fieldType'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldType']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['customFileds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldType']->key => $_smarty_tpl->tpl_vars['fieldType']->value){
$_smarty_tpl->tpl_vars['fieldType']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldName']->value = $_smarty_tpl->tpl_vars['fieldType']->key;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldType']->value,'_name'=>$_smarty_tpl->tpl_vars['fieldName']->value,'_ownerName'=>$_smarty_tpl->tpl_vars['_widgetCustomName']->value), 0);?>

    <?php } ?>
        </div>
    
        <div class="MPWSFormFooter">
    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resource'=>'custom','_ownerName'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['controls']), 0);?>

        </div>
    
    </form>

    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
         
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_widgetName']->value))."FormActionBackToRecords",'_ownerName'=>smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_widgetName']->value),'_action'=>'BackToRecords','_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['REFERER'],'_single'=>true,'_mode'=>'system'), 0);?>

    </div>

</div><?php }} ?>