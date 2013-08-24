<?php /* Smarty version Smarty-3.1.11, created on 2013-08-24 14:40:19
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html" */ ?>
<?php /*%%SmartyHeaderCode:144146947952063462e8a987-03971636%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67ea9f35dff21e59593cdbd7ee49a730e06da33f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html',
      1 => 1377344416,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '144146947952063462e8a987-03971636',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_52063463050908_83616469',
  'variables' => 
  array (
    'CURRENT' => 0,
    '_controlOwner' => 0,
    '_confing' => 0,
    '_actionName' => 0,
    '_resourceOwner' => 0,
    '_data' => 0,
    'fieldKey' => 0,
    'rowIndex' => 0,
    'actionName' => 0,
    'rowEntry' => 0,
    'cellEntry' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52063463050908_83616469')) {function content_52063463050908_83616469($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>

<?php $_smarty_tpl->tpl_vars['_controlOwner'] = new Smarty_variable('DataTable', null, 0);?>

<div id="MPWSComponentDataTableID" class="MPWSComponent MPWSComponentDataTable">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>$_smarty_tpl->tpl_vars['_controlOwner']->value), 0);?>




<div class="MPWSBlock MPWSBlockTopActions">

<?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions'])){?>
    <?php  $_smarty_tpl->tpl_vars['_actionName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_actionName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_actionName']->key => $_smarty_tpl->tpl_vars['_actionName']->value){
$_smarty_tpl->tpl_vars['_actionName']->_loop = true;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>$_smarty_tpl->tpl_vars['_actionName']->value,'_controlOwner'=>((string)$_smarty_tpl->tpl_vars['_controlOwner']->value)."TopAction",'_action'=>$_smarty_tpl->tpl_vars['_actionName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true,'_mode'=>'system'), 0);?>

    <?php } ?>
<?php }?>
</div>

<?php if (count($_smarty_tpl->tpl_vars['_data']->value)==0){?>

    <div class="MPWSBlock MPWSRenderModeError">
        <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_dataTableViewDataEmpty;?>
</span>
    </div>

<?php }elseif(count($_smarty_tpl->tpl_vars['_data']->value)>0){?>
    
    <?php $_smarty_tpl->_capture_stack[0][] = array("bunch_actions", null, null); ob_start(); ?>

        <span class="MPWSDataTableCellAction MPWSDataTableCellActionBunch MPWSDataTableCellActionBunchEdit">
        <?php if (in_array("bunch-edit",$_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions'])){?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'checkbox','_name'=>'BunchEdit','_action'=>'bunch-edit','_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true,'_mode'=>'system'), 0);?>

        <?php }?>
        </span>

        <span class="MPWSDataTableCellAction MPWSDataTableCellActionBunch MPWSDataTableCellActionBunchDelete">
        <?php if (in_array("bunch-edit",$_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions'])){?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'checkbox','_name'=>'BunchDelete','_action'=>'bunch-delete','_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true,'_mode'=>'system'), 0);?>

        <?php }?>
        </span>

    <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

    <div class="MPWSDataTableRows">
    
    <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['displayCaptions'])&&$_smarty_tpl->tpl_vars['_confing']->value['datatable']['displayCaptions']){?>
        <div class="MPWSDataTableRow MPWSDataTableRowCaptions">


        <?php if (!empty($_smarty_tpl->tpl_vars['_confing']->value['datatable']['perRecrodActions'])){?>
            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellActions">
                <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_dataTableViewCaptionActions;?>

                <?php echo Smarty::$_smarty_vars['capture']['bunch_actions'];?>

            </div>
        <?php }?>

        <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields'])){?>
            <?php  $_smarty_tpl->tpl_vars['fieldKey'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldKey']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldKey']->key => $_smarty_tpl->tpl_vars['fieldKey']->value){
$_smarty_tpl->tpl_vars['fieldKey']->_loop = true;
?>
                <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value);?>
">
                    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_caption".((string)$_smarty_tpl->tpl_vars['fieldKey']->value)};?>

                </div>
            <?php } ?>
        <?php }else{ ?>
            <?php  $_smarty_tpl->tpl_vars['cellEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cellEntry']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_data']->value[0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cellEntry']->key => $_smarty_tpl->tpl_vars['cellEntry']->value){
$_smarty_tpl->tpl_vars['cellEntry']->_loop = true;
?>
                <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['cellEntry']->key);?>
">
                    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_caption".((string)$_smarty_tpl->tpl_vars['cellEntry']->key)};?>

                </div>
            <?php } ?>
        <?php }?>
        </div>
    <?php }?>

    <?php  $_smarty_tpl->tpl_vars['rowEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rowEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['rowIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rowEntry']->key => $_smarty_tpl->tpl_vars['rowEntry']->value){
$_smarty_tpl->tpl_vars['rowEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['rowIndex']->value = $_smarty_tpl->tpl_vars['rowEntry']->key;
?>
        <div class="MPWSDataTableRow MPWSDataTableRow<?php echo $_smarty_tpl->tpl_vars['rowIndex']->value;?>
">

        <?php if (!empty($_smarty_tpl->tpl_vars['_confing']->value['datatable']['perRecrodActions'])){?>
            <div class="MPWSDataTableCell MPWSDataTableCellActions">
            <?php  $_smarty_tpl->tpl_vars['_actionName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_actionName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['perRecrodActions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_actionName']->key => $_smarty_tpl->tpl_vars['_actionName']->value){
$_smarty_tpl->tpl_vars['_actionName']->_loop = true;
?>
                <span class="MPWSDataTableCellAction MPWSDataTableCellAction<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['actionName']->value,0,1);?>
">
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>$_smarty_tpl->tpl_vars['_actionName']->value,'_controlOwner'=>((string)$_smarty_tpl->tpl_vars['_controlOwner']->value)."RowAction",'_action'=>$_smarty_tpl->tpl_vars['_actionName']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_single'=>true,'_oid'=>$_smarty_tpl->tpl_vars['rowEntry']->value['ID'],'_mode'=>'system'), 0);?>

                </span>
            <?php } ?>
            <?php echo Smarty::$_smarty_vars['capture']['bunch_actions'];?>

            </div>
        <?php }?>

        <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields'])){?>
            
            <?php  $_smarty_tpl->tpl_vars['fieldKey'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldKey']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldKey']->key => $_smarty_tpl->tpl_vars['fieldKey']->value){
$_smarty_tpl->tpl_vars['fieldKey']->_loop = true;
?>
                <div class="MPWSDataTableCell MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['rowEntry']->value[$_smarty_tpl->tpl_vars['fieldKey']->value];?>
</div>
            <?php } ?>
        <?php }else{ ?>
            
            <?php  $_smarty_tpl->tpl_vars['cellEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cellEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rowEntry']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cellEntry']->key => $_smarty_tpl->tpl_vars['cellEntry']->value){
$_smarty_tpl->tpl_vars['cellEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldKey']->value = $_smarty_tpl->tpl_vars['cellEntry']->key;
?>
                <div class="MPWSDataTableCell MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['cellEntry']->value;?>
</div>
            <?php } ?>
        <?php }?>
        </div>
    <?php } ?>
    
    </div>

<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Data object does not have content to render",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?>
</div><?php }} ?>