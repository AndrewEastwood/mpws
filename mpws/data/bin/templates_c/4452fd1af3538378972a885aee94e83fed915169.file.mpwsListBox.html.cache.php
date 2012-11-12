<?php /* Smarty version Smarty-3.1.11, created on 2012-11-09 08:59:57
         compiled from "/var/www/mpws/web/default/v1.0/template/control/mpwsListBox.html" */ ?>
<?php /*%%SmartyHeaderCode:20250405415099406192aaa8-41977546%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4452fd1af3538378972a885aee94e83fed915169' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsListBox.html',
      1 => 1352396826,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20250405415099406192aaa8-41977546',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5099406194f383_52730393',
  'variables' => 
  array (
    '_name' => 0,
    '_resourceOwner' => 0,
    '_numLines' => 0,
    '_checkboxes' => 0,
    '_singleEdit' => 0,
    '_captions' => 0,
    '_initValues' => 0,
    '_customValueHolder' => 0,
    '_numerateLines' => 0,
    '_useCheckbox' => 0,
    '_listCaptions' => 0,
    '_caption' => 0,
    '_innerCaptions' => 0,
    '_key' => 0,
    'CURRENT' => 0,
    '_controlCssName' => 0,
    '_fieldCaption' => 0,
    '_items' => 0,
    '_renderMode' => 0,
    '_itemKey' => 0,
    '_itemEntry' => 0,
    '_lineNumber' => 0,
    '_useSingleEdit' => 0,
    '_controlName' => 0,
    '_val' => 0,
    '_controlCustomValueHolder' => 0,
    '_display' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5099406194f383_52730393')) {function content_5099406194f383_52730393($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['_controlName'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('ListBox', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_resourceOwner'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'control' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_numerateLines'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_numLines']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_useCheckbox'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_checkboxes']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_useSingleEdit'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_singleEdit']->value)===null||$tmp==='' ? true : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_listCaptions'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_captions']->value)===null||$tmp==='' ? array() : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_listInit'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_initValues']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_lineNumber'] = new Smarty_variable(1, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_innerCaptions'] = new Smarty_variable(array(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_initValues'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_initValues']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCustomValueHolder'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_customValueHolder']->value)===null||$tmp==='' ? 'use' : $tmp), null, 0);?>


<?php if ($_smarty_tpl->tpl_vars['_numerateLines']->value){?>
    <?php $_smarty_tpl->createLocalArrayVariable('_innerCaptions', null, 0);
$_smarty_tpl->tpl_vars['_innerCaptions']->value[] = 'RowNumber';?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['_useCheckbox']->value){?>
    <?php $_smarty_tpl->createLocalArrayVariable('_innerCaptions', null, 0);
$_smarty_tpl->tpl_vars['_innerCaptions']->value[] = 'ActionEdit';?>
    <?php $_smarty_tpl->createLocalArrayVariable('_innerCaptions', null, 0);
$_smarty_tpl->tpl_vars['_innerCaptions']->value[] = 'ActionRemove';?>
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['_listCaptions']->value)){?>
    <?php  $_smarty_tpl->tpl_vars['_caption'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_caption']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_listCaptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_caption']->key => $_smarty_tpl->tpl_vars['_caption']->value){
$_smarty_tpl->tpl_vars['_caption']->_loop = true;
?>
        <?php $_smarty_tpl->createLocalArrayVariable('_innerCaptions', null, 0);
$_smarty_tpl->tpl_vars['_innerCaptions']->value[] = $_smarty_tpl->tpl_vars['_caption']->value;?>
    <?php } ?>
    
    <?php  $_smarty_tpl->tpl_vars['_caption'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_caption']->_loop = false;
 $_smarty_tpl->tpl_vars['_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_innerCaptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_caption']->key => $_smarty_tpl->tpl_vars['_caption']->value){
$_smarty_tpl->tpl_vars['_caption']->_loop = true;
 $_smarty_tpl->tpl_vars['_key']->value = $_smarty_tpl->tpl_vars['_caption']->key;
?>
        <?php $_smarty_tpl->createLocalArrayVariable('_innerCaptions', null, 0);
$_smarty_tpl->tpl_vars['_innerCaptions']->value[$_smarty_tpl->tpl_vars['_key']->value] = $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_captionListBox".((string)$_smarty_tpl->tpl_vars['_caption']->value)};?>
    <?php } ?>
<?php }?>


<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
">
    <input type="hidden" name="mpws_field_owner" value="<?php echo $_smarty_tpl->tpl_vars['_name']->value;?>
">
    <ul class="MPWSList">
        
    
    <?php if (!empty($_smarty_tpl->tpl_vars['_innerCaptions']->value)&&!$_smarty_tpl->tpl_vars['_initValues']->value){?>
    <li class="MPWSListItem">
    <?php  $_smarty_tpl->tpl_vars['_fieldCaption'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_fieldCaption']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_innerCaptions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_fieldCaption']->key => $_smarty_tpl->tpl_vars['_fieldCaption']->value){
$_smarty_tpl->tpl_vars['_fieldCaption']->_loop = true;
?>
        <span class="MPWSText MPWSTextCaption"><?php echo $_smarty_tpl->tpl_vars['_fieldCaption']->value;?>
</span>
    <?php } ?>
    </li>
    <?php }?>

    
    <?php  $_smarty_tpl->tpl_vars['_itemEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_itemEntry']->_loop = false;
 $_smarty_tpl->tpl_vars['_itemKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_itemEntry']->key => $_smarty_tpl->tpl_vars['_itemEntry']->value){
$_smarty_tpl->tpl_vars['_itemEntry']->_loop = true;
 $_smarty_tpl->tpl_vars['_itemKey']->value = $_smarty_tpl->tpl_vars['_itemEntry']->key;
?>
    
        <?php if ($_smarty_tpl->tpl_vars['_renderMode']->value=='KEYS'){?>
            <?php $_smarty_tpl->tpl_vars['_display'] = new Smarty_variable($_smarty_tpl->tpl_vars['_itemKey']->value, null, 0);?>
            <?php $_smarty_tpl->tpl_vars['_val'] = new Smarty_variable($_smarty_tpl->tpl_vars['_itemEntry']->value, null, 0);?>
        <?php }else{ ?>
            <?php $_smarty_tpl->tpl_vars['_display'] = new Smarty_variable($_smarty_tpl->tpl_vars['_itemEntry']->value, null, 0);?>
            <?php $_smarty_tpl->tpl_vars['_val'] = new Smarty_variable($_smarty_tpl->tpl_vars['_itemKey']->value, null, 0);?>
        <?php }?>

        <li class="MPWSListItem">
        <?php if ($_smarty_tpl->tpl_vars['_numerateLines']->value){?>
            <span class="MPWSText MPWSLineNumer"><?php echo $_smarty_tpl->tpl_vars['_lineNumber']->value++;?>
</span>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['_useCheckbox']->value){?>
            <?php if ($_smarty_tpl->tpl_vars['_useSingleEdit']->value){?>
            <input type="radio" name="<?php echo ((string)$_smarty_tpl->tpl_vars['_controlName']->value)."_edit";?>
" value="<?php echo $_smarty_tpl->tpl_vars['_val']->value;?>
"/>
            <input type="checkbox" name="<?php echo ((string)$_smarty_tpl->tpl_vars['_controlName']->value)."_remove";?>
[]" value="<?php echo $_smarty_tpl->tpl_vars['_val']->value;?>
"/>
            <?php }else{ ?>
            <input type="checkbox" name="<?php echo ((string)$_smarty_tpl->tpl_vars['_controlName']->value)."_edit";?>
[]" value="<?php echo $_smarty_tpl->tpl_vars['_val']->value;?>
"/>
            <?php }?>
        <?php }elseif($_smarty_tpl->tpl_vars['_initValues']->value){?>
            <input type="hidden" name="<?php echo ((string)$_smarty_tpl->tpl_vars['_controlName']->value)."_".((string)$_smarty_tpl->tpl_vars['_controlCustomValueHolder']->value);?>
[]" value="<?php echo $_smarty_tpl->tpl_vars['_val']->value;?>
"/>
        <?php }?>
            <span class="MPWSText MPWSLineValue"><?php echo $_smarty_tpl->tpl_vars['_display']->value;?>
</span>
        </li>
    <?php } ?>
    </ul>
</div><?php }} ?>