<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 10:32:45
         compiled from "/var/www/mpws/web/default/v1.0/template/component/errorFieldsList.html" */ ?>
<?php /*%%SmartyHeaderCode:2035188942507bbc1da558d3-15102203%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a4be5af2c477393048ef105d5ac3d9160ff22e2' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/errorFieldsList.html',
      1 => 1350280496,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2035188942507bbc1da558d3-15102203',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    '_fields' => 0,
    '_ownerName' => 0,
    '_errorFieldName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507bbc1db38d42_15723877',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507bbc1db38d42_15723877')) {function content_507bbc1db38d42_15723877($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div class="MPWSComponent MPWSComponentErrorFieldsList">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleHeader, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_validatorHeader), 0);?>

    <ul class="MPWSList">
    <?php  $_smarty_tpl->tpl_vars['_errorFieldName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_errorFieldName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_errorFieldName']->key => $_smarty_tpl->tpl_vars['_errorFieldName']->value){
$_smarty_tpl->tpl_vars['_errorFieldName']->_loop = true;
?>
        <li class="MPWSListItem"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_custom_validatorErrorAt".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_ownerName']->value)).((string)$_smarty_tpl->tpl_vars['_errorFieldName']->value)};?>
</li>
    <?php } ?>
    </ul>
</div><?php }} ?>