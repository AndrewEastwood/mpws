<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:58
         compiled from "/var/www/mpws/web/default/v1.0/template/component/pagingBar.html" */ ?>
<?php /*%%SmartyHeaderCode:1717060221507ba5e6cd00f1-05075779%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e98dc992345d0b598994ca191b79a051180fa317' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pagingBar.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1717060221507ba5e6cd00f1-05075779',
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
  'unifunc' => 'content_507ba5e6d412b1_14884751',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e6d412b1_14884751')) {function content_507ba5e6d412b1_14884751($_smarty_tpl) {?><div id="MPWSComponenPagingBarID" class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSBlock MPWSBlockSummary">
        <div class="MPWSDataRow">
            <label class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryFilteredRecords;?>
</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['AVAILABLE'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryTotalRecords;?>
</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['TOTAL'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryPageSize;?>
</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['LIMIT'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryPageCount;?>
</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['PAGES'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_pagingBarSummaryCurrentPage;?>
</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['CURRENT'];?>
</span>
        </div>
    </div>
    
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
    
    <div class="MPWSBlock MPWSBlockPageLinks">
    <?php  $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link']->_loop = false;
 $_smarty_tpl->tpl_vars['pageIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['LINKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link']->key => $_smarty_tpl->tpl_vars['link']->value){
$_smarty_tpl->tpl_vars['link']->_loop = true;
 $_smarty_tpl->tpl_vars['pageIndex']->value = $_smarty_tpl->tpl_vars['link']->key;
?>
    <a href="?<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
" class="MPWSLink MPWSLinkPaging"><?php echo $_smarty_tpl->tpl_vars['pageIndex']->value;?>
</a>
    <?php } ?>
    </div>
    
</div><?php }} ?>