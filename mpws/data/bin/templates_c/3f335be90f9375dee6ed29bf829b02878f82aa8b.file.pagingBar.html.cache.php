<?php /* Smarty version Smarty-3.1.11, created on 2013-08-10 15:39:12
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html" */ ?>
<?php /*%%SmartyHeaderCode:508676489520634700f2c16-91683576%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '508676489520634700f2c16-91683576',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    '_data' => 0,
    'link' => 0,
    'edgeName' => 0,
    'pageIndex' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206347016b4e0_34075011',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206347016b4e0_34075011')) {function content_5206347016b4e0_34075011($_smarty_tpl) {?><div id="MPWSComponenPagingBarID" class="MPWSComponent MPWSComponenPagingBar">
    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryFilteredRecords,'_value'=>$_smarty_tpl->tpl_vars['_data']->value['AVAILABLE']), 0);?>

    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryTotalRecords,'_value'=>$_smarty_tpl->tpl_vars['_data']->value['TOTAL']), 0);?>


    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryPageSize,'_value'=>$_smarty_tpl->tpl_vars['_data']->value['LIMIT']), 0);?>

    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryPageCount,'_value'=>$_smarty_tpl->tpl_vars['_data']->value['PAGES']), 0);?>

    
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryCurrentPage,'_value'=>$_smarty_tpl->tpl_vars['_data']->value['CURRENT']), 0);?>


    <div class="MPWSWrapper">
        
    <?php if (!empty($_smarty_tpl->tpl_vars['_data']->value['EDGES'])){?>
    <div class="MPWSBlock MPWSBlockEdgeLinks">
    <?php  $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link']->_loop = false;
 $_smarty_tpl->tpl_vars['edgeName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['EDGES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link']->key => $_smarty_tpl->tpl_vars['link']->value){
$_smarty_tpl->tpl_vars['link']->_loop = true;
 $_smarty_tpl->tpl_vars['edgeName']->value = $_smarty_tpl->tpl_vars['link']->key;
?>
        <a href="?<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
" class="MPWSLink MPWSLinkPaging"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_component_pagingBarEdgeLink".((string)$_smarty_tpl->tpl_vars['edgeName']->value)};?>
</a>
    <?php } ?>
    </div>
    <?php }?>
    
    <?php if (!empty($_smarty_tpl->tpl_vars['_data']->value['LINKS'])&&count($_smarty_tpl->tpl_vars['_data']->value['LINKS'])>1){?>
    <div class="MPWSBlock MPWSBlockPageLinks">
    <?php  $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link']->_loop = false;
 $_smarty_tpl->tpl_vars['pageIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['LINKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link']->key => $_smarty_tpl->tpl_vars['link']->value){
$_smarty_tpl->tpl_vars['link']->_loop = true;
 $_smarty_tpl->tpl_vars['pageIndex']->value = $_smarty_tpl->tpl_vars['link']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['_data']->value['CURRENT']==$_smarty_tpl->tpl_vars['pageIndex']->value){?>
            <a href="?<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
" class="MPWSLink MPWSLinkPaging MPWSLinkPagingActive"><?php echo $_smarty_tpl->tpl_vars['pageIndex']->value;?>
</a>
        <?php }else{ ?>
            <a href="?<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
" class="MPWSLink MPWSLinkPaging"><?php echo $_smarty_tpl->tpl_vars['pageIndex']->value;?>
</a>
        <?php }?>
    <?php } ?>
    </div>
    <?php }?>

    </div>
    
</div><?php }} ?>