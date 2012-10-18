<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 08:49:22
         compiled from "/var/www/mpws/web/default/v1.0/template/component/searchBox.html" */ ?>
<?php /*%%SmartyHeaderCode:17149719507ba5e6a38553-38073681%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '11949475bf39a92dd41d7b3b130e15a87110c9cd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/searchBox.html',
      1 => 1350539359,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17149719507ba5e6a38553-38073681',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5e6af4eb6_49306040',
  'variables' => 
  array (
    'CURRENT' => 0,
    '_confing' => 0,
    '_data' => 0,
    'field' => 0,
    '_fieldValue' => 0,
    '_ownerName' => 0,
    'sbKey' => 0,
    'sbVal' => 0,
    '_filterString' => 0,
    'srchl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e6af4eb6_49306040')) {function content_507ba5e6af4eb6_49306040($_smarty_tpl) {?>


<div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleHeader, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxHeader), 0);?>


    <form action="<?php echo $_smarty_tpl->tpl_vars['_confing']->value['searchbox']['formAction'];?>
" class="MPWSForm MPWSFormSearchBox" method="POST">
        
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
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_controlFieldSwitcher, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'textbox','_name'=>$_smarty_tpl->tpl_vars['field']->value,'_value'=>$_smarty_tpl->tpl_vars['_fieldValue']->value,'_ownerName'=>((string)$_smarty_tpl->tpl_vars['_ownerName']->value)."SearchBox",'_resource'=>'custom'), 0);?>

        <?php } ?>
        

        <?php if ($_smarty_tpl->tpl_vars['_data']->value['ACTIVE']){?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Search','Discard'),'_page'=>'SearchBox'), 0);?>

        <?php }else{ ?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Search'),'_page'=>'SearchBox'), 0);?>

        <?php }?>
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
$_smarty_tpl->tpl_vars['_filterString']->value[] = ((string)$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultFilterTextFieldPrefix)." ".((string)$_smarty_tpl->tpl_vars['sbKey']->value)." ".((string)$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultFilterTextValuePrefix)." ".((string)$_smarty_tpl->tpl_vars['sbVal']->value);?>
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
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_divRowLabelValue, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_label'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxResultMessage,'_value'=>Smarty::$_smarty_vars['capture']['searchBoxResultText']), 0);?>

        
    <?php }?>
    
</div><?php }} ?>