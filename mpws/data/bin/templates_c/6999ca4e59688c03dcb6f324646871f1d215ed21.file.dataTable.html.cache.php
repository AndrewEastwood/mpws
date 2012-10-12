<?php /* Smarty version Smarty-3.1.11, created on 2012-10-11 12:44:37
         compiled from "/var/www/mpws/web/default/v1.0/template/component/dataTable.html" */ ?>
<?php /*%%SmartyHeaderCode:118635605750769505458714-66314140%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6999ca4e59688c03dcb6f324646871f1d215ed21' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataTable.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '118635605750769505458714-66314140',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_ownerName' => 0,
    '_data' => 0,
    'CURRENT' => 0,
    '_confing' => 0,
    '__prop__' => 0,
    'fieldKey' => 0,
    'rowIndex' => 0,
    'actionName' => 0,
    'rowEntry' => 0,
    'cellEntry' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50769505534986_90893219',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50769505534986_90893219')) {function content_50769505534986_90893219($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>


<div id="MPWSComponentDataTableID" class="MPWSComponent MPWSComponentDataTable">


<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_custom_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value), null, 0);?>


<?php if (count($_smarty_tpl->tpl_vars['_data']->value)==0){?>

    <div class="MPWSBlock">
        <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_widget_dataTableViewDataEmpty;?>
</span>
    </div>

<?php }elseif(count($_smarty_tpl->tpl_vars['_data']->value)>0){?>


    <div class="MPWSDataTableRows">
    
    <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['displayCaptions'])&&$_smarty_tpl->tpl_vars['_confing']->value['datatable']['displayCaptions']){?>
        <div class="MPWSDataTableRow MPWSDataTableRowCaptions">


        <?php if (!empty($_smarty_tpl->tpl_vars['_confing']->value['datatable']['actions'])){?>
            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellActions"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."CaptionActions"};?>
</div>
        <?php }?>

        <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields'])){?>
            <?php  $_smarty_tpl->tpl_vars['fieldKey'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldKey']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldKey']->key => $_smarty_tpl->tpl_vars['fieldKey']->value){
$_smarty_tpl->tpl_vars['fieldKey']->_loop = true;
?>
                <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value,0,1);?>
">
                    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."Caption".((string)$_smarty_tpl->tpl_vars['fieldKey']->value)};?>

                </div>
            <?php } ?>
        <?php }else{ ?>
            <?php  $_smarty_tpl->tpl_vars['cellEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cellEntry']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_data']->value[0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cellEntry']->key => $_smarty_tpl->tpl_vars['cellEntry']->value){
$_smarty_tpl->tpl_vars['cellEntry']->_loop = true;
?>
                <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['cellEntry']->key,0,1);?>
">
                    <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."Caption".((string)$_smarty_tpl->tpl_vars['cellEntry']->key)};?>

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
            <?php  $_smarty_tpl->tpl_vars['actionName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['actionName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['perRecrodActions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['actionName']->key => $_smarty_tpl->tpl_vars['actionName']->value){
$_smarty_tpl->tpl_vars['actionName']->_loop = true;
?>
                <span class="MPWSDataTableCellAction<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['actionName']->value,0,1);?>
">
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_actionLink, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_ownerName'=>$_smarty_tpl->tpl_vars['_ownerName']->value,'_action'=>$_smarty_tpl->tpl_vars['actionName']->value,'_oid'=>$_smarty_tpl->tpl_vars['rowEntry']->value['ID']), 0);?>

                </span>
            <?php } ?>
            </div>
        <?php }?>

        <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields'])){?>
            
            <?php  $_smarty_tpl->tpl_vars['fieldKey'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldKey']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldKey']->key => $_smarty_tpl->tpl_vars['fieldKey']->value){
$_smarty_tpl->tpl_vars['fieldKey']->_loop = true;
?>
                <div class="MPWSDataTableCell MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value,0,1);?>
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
                <div class="MPWSDataTableCell MPWSDataTableCell<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value,0,1);?>
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