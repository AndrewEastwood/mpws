<?php /* Smarty version Smarty-3.1.11, created on 2013-08-27 02:34:25
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/searchBox.html" */ ?>
<?php /*%%SmartyHeaderCode:20628907785206346fbb3de7-25492788%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8353ed817f3a10aa18cb6a40909f78201e516c64' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/searchBox.html',
      1 => 1377560064,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20628907785206346fbb3de7-25492788',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206346fcb4bc0_35855080',
  'variables' => 
  array (
    'CURRENT' => 0,
    '_confing' => 0,
    '_data' => 0,
    'field' => 0,
    '_fieldValue' => 0,
    '_resourceOwner' => 0,
    '_controlOwner' => 0,
    'sbKey' => 0,
    'sbVal' => 0,
    '_filterString' => 0,
    'srchl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206346fcb4bc0_35855080')) {function content_5206346fcb4bc0_35855080($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_controlOwner'] = new Smarty_variable('SearchBox', null, 0);?>
<div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_resourceOwner'=>'display','_key'=>"searchBox"), 0);?>

    <form action="<?php echo $_smarty_tpl->tpl_vars['_confing']->value['searchbox']['formAction'];?>
" class="MPWSForm MPWSFormSearchBox" method="POST">
        <div class="MPWSFormBody">
        
        <?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
            <?php $_smarty_tpl->tpl_vars["_fieldValue"] = new Smarty_variable('', null, 0);?>
            <?php if ($_smarty_tpl->tpl_vars['_data']->value['ACTIVE']){?>
                <?php $_smarty_tpl->tpl_vars["_fieldValue"] = new Smarty_variable(libraryRequest::getPostFormField($_smarty_tpl->tpl_vars['field']->value), null, 0);?>
                <?php if (empty($_smarty_tpl->tpl_vars['_fieldValue']->value)&&isset($_smarty_tpl->tpl_vars['_data']->value['FILTER'][$_smarty_tpl->tpl_vars['field']->value])){?>
                    <?php $_smarty_tpl->tpl_vars['_fieldValue'] = new Smarty_variable(trim($_smarty_tpl->tpl_vars['_data']->value['FILTER'][$_smarty_tpl->tpl_vars['field']->value],'%'), null, 0);?>
                <?php }?>
            <?php }else{ ?>
                <?php $_smarty_tpl->tpl_vars["_fieldValue"] = new Smarty_variable(false, null, 0);?>
            <?php }?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'textbox','_name'=>$_smarty_tpl->tpl_vars['field']->value,'_value'=>$_smarty_tpl->tpl_vars['_fieldValue']->value,'_resourceOwner'=>$_smarty_tpl->tpl_vars['_resourceOwner']->value,'_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value), 0);?>

        <?php } ?>
        </div>
        
        <div class="MPWSFormFooter">
        
        <?php if ($_smarty_tpl->tpl_vars['_data']->value['ACTIVE']){?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Search','Discard'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value,'_resourceOwner'=>'control','_customCssClassNames'=>'btn'), 0);?>

        <?php }else{ ?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Search'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value,'_resourceOwner'=>'control','_customCssClassNames'=>'btn'), 0);?>

        <?php }?>
        </div>
    </form>

    
    <?php if ($_smarty_tpl->tpl_vars['_data']->value['ACTIVE']){?>
    
        <?php $_smarty_tpl->_capture_stack[0][] = array("searchBoxResultText", null, null); ob_start(); ?>
            <?php  $_smarty_tpl->tpl_vars['sbVal'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sbVal']->_loop = false;
 $_smarty_tpl->tpl_vars['sbKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['WORDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sbVal']->key => $_smarty_tpl->tpl_vars['sbVal']->value){
$_smarty_tpl->tpl_vars['sbVal']->_loop = true;
 $_smarty_tpl->tpl_vars['sbKey']->value = $_smarty_tpl->tpl_vars['sbVal']->key;
?>
                <?php $_smarty_tpl->createLocalArrayVariable('_filterString', null, 0);
$_smarty_tpl->tpl_vars['_filterString']->value[] = ((string)$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultFilterTextFieldPrefix)." <span class=\"label\">".((string)$_smarty_tpl->tpl_vars['sbKey']->value)."</span> ".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultFilterTextValuePrefix)." <span class=\"label label-info\">".((string)$_smarty_tpl->tpl_vars['sbVal']->value)."</span>";?>
            <?php } ?>
            <?php $_smarty_tpl->tpl_vars['srchl'] = new Smarty_variable(implode($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultFilterTextFieldValueSplitter,$_smarty_tpl->tpl_vars['_filterString']->value), null, 0);?>
            <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultFilterTextPrefix;?>
 <?php echo $_smarty_tpl->tpl_vars['srchl']->value;?>

        <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_dataRow, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultMessage,'_value'=>Smarty::$_smarty_vars['capture']['searchBoxResultText']), 0);?>

        
    <?php }?>
    
</div><?php }} ?>