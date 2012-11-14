<?php /* Smarty version Smarty-3.1.11, created on 2012-11-14 17:07:45
         compiled from "/var/www/mpws/web/default/v1.0/template/widget/baseDataRecordManager.html" */ ?>
<?php /*%%SmartyHeaderCode:17615430075098ef3032ce77-25780393%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e4dc7878013ddb8f47ac41df5072bfd313550ef' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/baseDataRecordManager.html',
      1 => 1352796441,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17615430075098ef3032ce77-25780393',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5098ef303c88b1_27316063',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    'CURRENT' => 0,
    '_widgetName' => 0,
    'DTV_CFG' => 0,
    'fieldName' => 0,
    'fieldValue' => 0,
    '_managerCount' => 0,
    'managerEntry' => 0,
    'managerID' => 0,
    '_formInnerAction' => 0,
    '_managerConfig' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5098ef303c88b1_27316063')) {function content_5098ef303c88b1_27316063($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("DataRecordManager", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? ((($tmp = @$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['RESOURCE'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['_widgetName']->value : $tmp)) : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_dataRecordManager".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'])}, null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD'][$_smarty_tpl->tpl_vars['DTV_CFG']->value['caption']]), 0);?>

    
    
    
    <div id="MPWSBlock MPWSBlockRecordOwner">
    <?php  $_smarty_tpl->tpl_vars['fieldValue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldValue']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldValue']->key => $_smarty_tpl->tpl_vars['fieldValue']->value){
$_smarty_tpl->tpl_vars['fieldValue']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldName']->value = $_smarty_tpl->tpl_vars['fieldValue']->key;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLabelValue','_name'=>$_smarty_tpl->tpl_vars['fieldName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_value'=>$_smarty_tpl->tpl_vars['fieldValue']->value), 0);?>

    <?php } ?>
    </div>

    <?php $_smarty_tpl->tpl_vars['_managerCount'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['DTV_CFG']->value['managers']), null, 0);?>

    
    <?php if ($_smarty_tpl->tpl_vars['_managerCount']->value>1){?>
        <div class="MPWSBlock MPWSRenderModeTabs MPWSBlockManagers">
            <ul class="MPWSList MPWSListManagerTabs" mpws-activetab="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['COMMON']['ACTIVE_INDEX'];?>
">
                <?php  $_smarty_tpl->tpl_vars['managerEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['managerEntry']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DTV_CFG']->value['managers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['managerEntry']->key => $_smarty_tpl->tpl_vars['managerEntry']->value){
$_smarty_tpl->tpl_vars['managerEntry']->_loop = true;
?>
                <li class="MPWSListItem">
                    <a href="#MPWSBlockManager_<?php echo $_smarty_tpl->tpl_vars['managerEntry']->key;?>
_ID"><?php echo $_smarty_tpl->tpl_vars['managerEntry']->key;?>
</a>
                </li>
                <?php } ?>
            </ul>
    <?php }?>
    
    
    <?php  $_smarty_tpl->tpl_vars['managerEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['managerEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['managerID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['MANAGER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['managerEntry']->key => $_smarty_tpl->tpl_vars['managerEntry']->value){
$_smarty_tpl->tpl_vars['managerEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['managerID']->value = $_smarty_tpl->tpl_vars['managerEntry']->key;
?>
        <?php $_smarty_tpl->tpl_vars['_formInnerAction'] = new Smarty_variable($_smarty_tpl->tpl_vars['managerEntry']->value['EDIT_PAGE'], null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_managerConfig'] = new Smarty_variable($_smarty_tpl->tpl_vars['DTV_CFG']->value['managers'][$_smarty_tpl->tpl_vars['managerID']->value], null, 0);?>

        <div id="MPWSBlockManager_<?php echo $_smarty_tpl->tpl_vars['managerID']->value;?>
_ID" class="MPWSBlock MPWSBlockManager">
        
        <form action="" method="POST">
            <div class="MPWSFormHeader"></div>
            <div class="MPWSFormBody">
                <input type="hidden" name="mpws_field_manager_request_origin" value="<?php echo mb_strtolower($_smarty_tpl->tpl_vars['managerID']->value, 'UTF-8');?>
">
                <input type="hidden" name="mpws_field_manager_request_page_sender" value="<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_formInnerAction']->value, 'UTF-8');?>
">
                
                

                <?php if ($_smarty_tpl->tpl_vars['_managerConfig']->value['type']=='file'){?>

                    

                    <?php if ($_smarty_tpl->tpl_vars['_formInnerAction']->value=='NEW'){?>

                        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'textbox','_name'=>((string)$_smarty_tpl->tpl_vars['managerID']->value)."_newitem",'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true), 0);?>


                        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Save','Cancel'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>'control'), 0);?>


                    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=='EDIT'){?>

                        <div class="MPWSBlock MPWSBockReportScriptEditArea">
                            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsRTEWH, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['managerID']->value,'_type'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['editor']['type'],'_value'=>$_smarty_tpl->tpl_vars['managerEntry']->value['CONTENT']['DATA'],'_sources'=>$_smarty_tpl->tpl_vars['managerEntry']->value['CONTENT']['SOURCE'],'_useDivWrapper'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['editor']['divmode'],'_editLang'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['editor']['language'],'_jslib'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['editor']['jslib']), 0);?>

                        </div>

                    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=='REMOVE'){?>

                        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsListBox','_name'=>$_smarty_tpl->tpl_vars['managerID']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_items'=>$_smarty_tpl->tpl_vars['managerEntry']->value['CONTENT']['SOURCE'],'_renderMode'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['listbox']['displayMode'],'_initValues'=>true,'_customValueHolder'=>'removeable','_single'=>true), 0);?>


                        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Save','Cancel'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>'control'), 0);?>


                    <?php }elseif($_smarty_tpl->tpl_vars['_formInnerAction']->value=='LIST'){?>

                        <?php if (!empty($_smarty_tpl->tpl_vars['_managerConfig']->value['buttons']['top'])){?>
                            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['buttons']['top'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>'control'), 0);?>

                        <?php }?>

                        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsListBox','_name'=>$_smarty_tpl->tpl_vars['managerID']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_items'=>$_smarty_tpl->tpl_vars['managerEntry']->value['CONTENT']['SOURCE'],'_renderMode'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['listbox']['displayMode'],'_checkboxes'=>true,'_numLines'=>true,'_captions'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['listbox']['captions']), 0);?>


                        <?php if (!empty($_smarty_tpl->tpl_vars['_managerConfig']->value['buttons']['bottom'])){?>
                            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>$_smarty_tpl->tpl_vars['_managerConfig']->value['buttons']['bottom'],'_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_resourceOwner'=>'control'), 0);?>

                        <?php }?>

                    <?php }?>
                    
                <?php }elseif($_smarty_tpl->tpl_vars['_managerConfig']->value['type']=='record'){?>
                    
                    
                    RECORD MANAGER IS NOT IMPLEMENTED FOR TYPE: DB RECORD
                
                <?php }?>

                
            </div>
            <div class="MPWSFormFooter"></div>
        </form>
        
        </div>
        
        
    <?php } ?>
    
    
    
    <?php if ($_smarty_tpl->tpl_vars['_managerCount']->value>1){?>
        </div>
    <?php }?>
    
    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>'BackToRecords','_controlOwner'=>$_smarty_tpl->tpl_vars['_widgetName']->value,'_action'=>'BackToRecords','_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['REFERER'],'_single'=>true,'_mode'=>'system'), 0);?>

    </div>
</div><?php }} ?>