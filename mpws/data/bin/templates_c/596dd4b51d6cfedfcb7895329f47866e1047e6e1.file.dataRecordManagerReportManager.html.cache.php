<?php /* Smarty version Smarty-3.1.11, created on 2012-11-08 09:45:39
         compiled from "/var/www/mpws/web/plugin/reporting/template/widget/dataRecordManagerReportManager.html" */ ?>
<?php /*%%SmartyHeaderCode:9911254085098edd091a172-52902928%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '596dd4b51d6cfedfcb7895329f47866e1047e6e1' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/dataRecordManagerReportManager.html',
      1 => 1352360721,
      2 => 'file',
    ),
    '1e4dc7878013ddb8f47ac41df5072bfd313550ef' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/baseDataRecordManager.html',
      1 => 1352360677,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9911254085098edd091a172-52902928',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5098edd094c284_26253251',
  'variables' => 
  array (
    'CURRENT' => 0,
    '_resourceOwner' => 0,
    '_widgetName' => 0,
    'DTV_CFG' => 0,
    'fieldName' => 0,
    'fieldValue' => 0,
    '_managerCount' => 0,
    'managerID' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5098edd094c284_26253251')) {function content_5098edd094c284_26253251($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_widgetName'] = new Smarty_variable("DataRecordManager", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? ((($tmp = @$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['RESOURCE'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['_widgetName']->value : $tmp)) : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars["DTV_CFG"] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectConfiguration_widget_dataRecordManager".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['NAME'])}, null, 0);?>

<div id="MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSWidget MPWSWidget<?php echo $_smarty_tpl->tpl_vars['_widgetName']->value;?>
 MPWSWidget<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD'][$_smarty_tpl->tpl_vars['DTV_CFG']->value['caption']]), 0);?>

    
    
    
    <?php  $_smarty_tpl->tpl_vars['fieldValue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldValue']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['RECORD']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldValue']->key => $_smarty_tpl->tpl_vars['fieldValue']->value){
$_smarty_tpl->tpl_vars['fieldValue']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldName']->value = $_smarty_tpl->tpl_vars['fieldValue']->key;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLabelValue','_name'=>$_smarty_tpl->tpl_vars['fieldName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_value'=>$_smarty_tpl->tpl_vars['fieldValue']->value), 0);?>

    <?php } ?>

    
        
    
    
    
    
    <?php $_smarty_tpl->tpl_vars['_managerCount'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['DTV_CFG']->value['manager']), null, 0);?>

    
    <?php if ($_smarty_tpl->tpl_vars['_managerCount']->value>1){?>
        <div id="MPWSBlock MPWSBlockManagerTabs">
            <ul class="MPWSList">
                <?php  $_smarty_tpl->tpl_vars['managerEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['managerEntry']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DTV_CFG']->value['manager']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['managerEntry']->key => $_smarty_tpl->tpl_vars['managerEntry']->value){
$_smarty_tpl->tpl_vars['managerEntry']->_loop = true;
?>
                <li><?php echo $_smarty_tpl->tpl_vars['managerEntry']->key;?>
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
        <div id="MPWSBlock MPWSBlockManager">
        
        <form action="" method="POST">
            <div class="MPWSFormHeader"></div>
            <div class="MPWSFormBody">
                <input type="hidden" name="mpws_field_manager_request_origin" value="<?php echo mb_strtolower($_smarty_tpl->tpl_vars['managerID']->value, 'UTF-8');?>
">
                

    


        


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