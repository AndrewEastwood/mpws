<?php /* Smarty version Smarty-3.1.11, created on 2012-10-29 17:54:26
         compiled from "/var/www/mpws/web/default/v1.0/template/component/standaloneRecordRemoval.html" */ ?>
<?php /*%%SmartyHeaderCode:85396023508ea64a576596-88512273%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45a2755ceb1109886c40db8da316ac650d9ba0db' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/standaloneRecordRemoval.html',
      1 => 1351526061,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85396023508ea64a576596-88512273',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508ea64a605869_91174076',
  'variables' => 
  array (
    '_resourceOwner' => 0,
    '_controlOwner' => 0,
    '_formAction' => 0,
    '_formInnerAction' => 0,
    'DTV_CFG' => 0,
    'CURRENT' => 0,
    '_confing' => 0,
    '_data' => 0,
    'fieldName' => 0,
    'fieldValue' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508ea64a605869_91174076')) {function content_508ea64a605869_91174076($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars['_controlOwner'] = new Smarty_variable('StandaloneRecordView', null, 0);?> 

<div id="MPWSComponent<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
ID" class="MPWSComponent MPWSComponent<?php echo $_smarty_tpl->tpl_vars['_controlOwner']->value;?>
 MPWSComponentDataTable<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_resourceOwner']->value);?>
">
    
    <form action="?<?php echo $_smarty_tpl->tpl_vars['_formAction']->value;?>
" name="data_edit_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_resourceOwner']->value, 'UTF-8');?>
" method="<?php echo $_smarty_tpl->tpl_vars['DTV_CFG']->value['form'][$_smarty_tpl->tpl_vars['_formInnerAction']->value]['method'];?>
" class="MPWSForm MPWSFormEditor MPWSFormEditorPage<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_formInnerAction']->value,0,1);?>
">
        <div class="MPWSFormHeader">
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['_data']->value[$_smarty_tpl->tpl_vars['_confing']->value['standalone']['caption']]), 0);?>

        </div>
        <div class="MPWSFormBody">
            <input type="hidden" name="mpws_field_session" value="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['SESSION'];?>
"/>
            <?php  $_smarty_tpl->tpl_vars['fieldValue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldValue']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldValue']->key => $_smarty_tpl->tpl_vars['fieldValue']->value){
$_smarty_tpl->tpl_vars['fieldValue']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldName']->value = $_smarty_tpl->tpl_vars['fieldValue']->key;
?>
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLabelValue','_name'=>$_smarty_tpl->tpl_vars['fieldName']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_value'=>$_smarty_tpl->tpl_vars['fieldValue']->value), 0);?>

            <?php } ?>
        </div>
        <div class="MPWSFormFooter">
            
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Remove','Cancel'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value,'_resourceOwner'=>'control'), 0);?>

        </div>
    </form>
    
    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
         
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_name'=>'BackToRecords','_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value,'_action'=>'BackToRecords','_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_href'=>$_smarty_tpl->tpl_vars['CURRENT']->value['SOURCE']['DATA']['REFERER'],'_single'=>true,'_mode'=>'system'), 0);?>

    </div>
</div><?php }} ?>