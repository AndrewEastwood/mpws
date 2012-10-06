<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 01:23:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html" */ ?>
<?php /*%%SmartyHeaderCode:173586352350709c46534599-82609507%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1349562191,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173586352350709c46534599-82609507',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50709c46537650_94390935',
  'variables' => 
  array (
    '_data' => 0,
    'link' => 0,
    'pageIndex' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50709c46537650_94390935')) {function content_50709c46537650_94390935($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSComponentSummary">
        <div class="MPWSComponentSummaryRow">
            <label>Filtered Records:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['AVAILABLE'];?>
</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Total Records:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['TOTAL'];?>
</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Table Always Shows:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['LIMIT'];?>
</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Total Pages:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['PAGES'];?>
</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Current Page:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['CURRENT'];?>
</span>
        </div>
    </div>
    
    
    <div class="MPWSBlock MPWSBlockEdgeLinks">
    <?php  $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link']->_loop = false;
 $_smarty_tpl->tpl_vars['pageIndex'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['EDGES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link']->key => $_smarty_tpl->tpl_vars['link']->value){
$_smarty_tpl->tpl_vars['link']->_loop = true;
 $_smarty_tpl->tpl_vars['pageIndex']->value = $_smarty_tpl->tpl_vars['link']->key;
?>
    <a href="?<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
" class="MPWSLink MPWSLinkPaging"><?php echo $_smarty_tpl->tpl_vars['pageIndex']->value;?>
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