<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 14:59:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html" */ ?>
<?php /*%%SmartyHeaderCode:90036655550716ea25499c5-77887591%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1349562325,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '90036655550716ea25499c5-77887591',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_data' => 0,
    'link' => 0,
    'pageIndex' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50716ea2573183_73949656',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50716ea2573183_73949656')) {function content_50716ea2573183_73949656($_smarty_tpl) {?><div class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSBlock MPWSBlockSummary">
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Filtered Records:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['AVAILABLE'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Total Records:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['TOTAL'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Table Always Shows:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['LIMIT'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Total Pages:</label>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['_data']->value['PAGES'];?>
</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Current Page:</label>
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