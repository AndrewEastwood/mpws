<?php /* Smarty version Smarty-3.1.11, created on 2012-10-29 09:21:04
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/baseDataEditor.html" */ ?>
<?php /*%%SmartyHeaderCode:83151832150877fe3db6d92-06185155%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9e95e057f99621ee7164be23b6fedf8bc2d1fb7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/baseDataEditor.html',
      1 => 1351493136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '83151832150877fe3db6d92-06185155',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50877fe40433e5_38902018',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_widgetName' => 0,
    'CURRENT' => 0,
    '_formInnerAction' => 0,
    'DTV_CFG' => 0,
    '_formAction' => 0,
    'fieldEntry' => 0,
    '_editFieldRenderMode' => 0,
    '_controlValue' => 0,
    'fieldType' => 0,
    'fieldName' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50877fe40433e5_38902018')) {function content_50877fe40433e5_38902018($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>

<?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("DataEditor", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['_widgetName']->value : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)}, null, 0);?>

<div id="MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_resourceOwner']->value;?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_resourceOwner']->value;?>
">
    
    <?php $_smarty_tpl->tpl_vars['_formInnerAction'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['EDIT_PAGE'], null, 0);?>

    
    <?php if ($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ISNEW']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_title, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_customText'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_widget_dataEditorStateNewPage".((string)mb_strtoupper($_smarty_tpl->tpl_vars['_formInnerAction']->value, 'UTF-8'))}), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_title, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_customText'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_widget_dataEditorStateExistedPage".((string)mb_strtoupper($_smarty_tpl->tpl_vars['_formInnerAction']->value, 'UTF-8'))}), 0);?>

    <?php }?>
    
    
    <?php if (!$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['VALID']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_messageList, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_messages'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['ERRORS'],'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_controlOwner'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."Validator"), 0);?>

    <?php }?>
    
    <?php $_smarty_tpl->tpl_vars['_formAction'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['action'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FORM_ACTION'] : $tmp), null, 0);?>
    
    
    <form action="?<?php echo $_smarty_tpl->tpl_vars['_formAction']->value;?>
" name="data_edit_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_resourceOwner']->value, 'UTF-8');?>
" method="<?php echo $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['method'];?>
" class="MPWSForm MPWSFormEditor MPWSFormEditorPage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_formInnerAction']->value,0,1);?>
">
        <div class="MPWSFormBody">

        <input type="hidden" name="mpws_field_session" value="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SESSION'];?>
"/>
    
    
    <?php if ($_smarty_tpl->tpl_vars['_formInnerAction']->value=="new"){?>
    
        
        
        <?php  $_smarty_tpl->tpl_vars['fieldEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldEntry']->key => $_smarty_tpl->tpl_vars['fieldEntry']->value){
$_smarty_tpl->tpl_vars['fieldEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldIndex']->value = $_smarty_tpl->tpl_vars['fieldEntry']->key;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_value'=>false), 0);?>

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
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_render'=>$_smarty_tpl->tpl_vars['_editFieldRenderMode']->value), 0);?>

            <?php }else{ ?>
                
                <?php if (!in_array($_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],$_smarty_tpl->tpl_vars['DTV_CFG']->value['fields']['skipIfEditExisted'])){?>
                    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SOURCE'][$_smarty_tpl->tpl_vars['fieldEntry']->value['Field']], null, 0);?>
                    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value,'_render'=>$_smarty_tpl->tpl_vars['_editFieldRenderMode']->value), 0);?>

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
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Type'],'_name'=>$_smarty_tpl->tpl_vars['fieldEntry']->value['Field'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_renderMode'=>'hidden'), 0);?>

        <?php } ?>
        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="save"){?>
    
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."SaveMain"), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."SaveSubText"), 0);?>

        
    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=="cancel"){?>
    
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."CancelMain"), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_text, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>((string)$_smarty_tpl->tpl_vars['_widgetName']->value)."CancelSubText"), 0);?>

        
    <?php }?>
    
    
    <?php  $_smarty_tpl->tpl_vars['fieldType'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldType']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['customFileds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldType']->key => $_smarty_tpl->tpl_vars['fieldType']->value){
$_smarty_tpl->tpl_vars['fieldType']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldName']->value = $_smarty_tpl->tpl_vars['fieldType']->key;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>$_smarty_tpl->tpl_vars['fieldType']->value,'_name'=>$_smarty_tpl->tpl_vars['fieldName']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value), 0);?>

    <?php } ?>
        </div>
    
        <div class="MPWSFormFooter">
    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>$_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['controls'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>'control'), 0);?>

        </div>
    
    </form>

    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
         
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>'BackToRecords','_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_action'=>'BackToRecords','_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['REFERER'],'_single'=>true,'_mode'=>'system'), 0);?>

    </div>

</div><?php }} ?>