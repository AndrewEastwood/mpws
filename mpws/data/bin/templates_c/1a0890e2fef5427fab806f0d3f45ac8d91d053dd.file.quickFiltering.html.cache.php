<?php /* Smarty version Smarty-3.1.11, created on 2012-10-22 16:10:22
         compiled from "/var/www/mpws/web/default/v1.0/template/component/quickFiltering.html" */ ?>
<?php /*%%SmartyHeaderCode:73807479050815cfd211a06-77748137%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a0890e2fef5427fab806f0d3f45ac8d91d053dd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1350911360,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '73807479050815cfd211a06-77748137',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50815cfd2ced69_19895629',
  'variables' => 
  array (
    'CURRENT' => 0,
    'componentName' => 0,
    '_confing' => 0,
    'qfEntry' => 0,
    '_requestKey' => 0,
    '_keyAsc' => 0,
    '_keyDesc' => 0,
    '_linkTextKey' => 0,
    '_ownerName' => 0,
    '_filterAction' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50815cfd2ced69_19895629')) {function content_50815cfd2ced69_19895629($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>


<?php $_smarty_tpl->tpl_vars['componentName'] = new Smarty_variable("quickFiltering", null, 0);?>

<div id="MPWSComponenQuickFilteringID" class="MPWSComponent MPWSComponenQuickFiltering">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>$_smarty_tpl->tpl_vars['componentName']->value), 0);?>

    <?php  $_smarty_tpl->tpl_vars['qfEntry'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['qfEntry']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['filtering']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['qfEntry']->key => $_smarty_tpl->tpl_vars['qfEntry']->value){
$_smarty_tpl->tpl_vars['qfEntry']->_loop = true;
?>

        <?php $_smarty_tpl->tpl_vars['_keyAsc'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['qfEntry']->value).".asc", null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_keyDesc'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['qfEntry']->value).".desc", null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_requestKey'] = new Smarty_variable(libraryRequest::getValue($_smarty_tpl->tpl_vars['_confing']->value['filtering']['sortKey']), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_filterAction'] = new Smarty_variable(libraryRequest::getNewUrl('sort',libraryUtils::valueSelect($_smarty_tpl->tpl_vars['_requestKey']->value,$_smarty_tpl->tpl_vars['_keyAsc']->value,$_smarty_tpl->tpl_vars['_keyDesc']->value,$_smarty_tpl->tpl_vars['_keyAsc']->value)), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['_linkTextKey'] = new Smarty_variable(libraryUtils::valueSelect($_smarty_tpl->tpl_vars['_requestKey']->value,$_smarty_tpl->tpl_vars['_keyAsc']->value,"DESC","ASC"), null, 0);?>
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'mpwsLinkAction','_resource'=>'custom','_name'=>((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['componentName']->value)).((string)$_smarty_tpl->tpl_vars['_linkTextKey']->value),'_ownerName'=>((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['componentName']->value))."Of".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_ownerName']->value)),'_href'=>"?".((string)$_smarty_tpl->tpl_vars['_filterAction']->value)."#MPWSComponenQuickFilteringID",'_replaceControlNameInLabelBy'=>$_smarty_tpl->tpl_vars['qfEntry']->value,'_action'=>$_smarty_tpl->tpl_vars['_linkTextKey']->value,'_mode'=>'system'), 0);?>


    <?php } ?>
</div><?php }} ?>