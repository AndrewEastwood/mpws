<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 12:40:12
         compiled from "/var/www/mpws/web/default/v1.0/template/component/databaseField.html" */ ?>
<?php /*%%SmartyHeaderCode:2894684765076952779b027-96417852%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '79b0ca9e0f85b697931ecf7e0b9b28b1097a6592' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/databaseField.html',
      1 => 1350033607,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2894684765076952779b027-96417852',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507695277ad252_52481206',
  'variables' => 
  array (
    '_type' => 0,
    'CURRENT' => 0,
    '_name' => 0,
    '_ownerName' => 0,
    '_standartControlType' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507695277ad252_52481206')) {function content_507695277ad252_52481206($_smarty_tpl) {?>


<div class="MPWSComponent MPWSComponentDataBaseField">

<?php $_smarty_tpl->_capture_stack[0][] = array("control", null, null); ob_start(); ?>
    <?php if ($_smarty_tpl->tpl_vars['_type']->value=='text'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextArea', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleTextArea, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'varchar')){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>glGetOnlyNums($_smarty_tpl->tpl_vars['_type']->value)), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='tinyint(1)'||$_smarty_tpl->tpl_vars['_type']->value=='bool'||$_smarty_tpl->tpl_vars['_type']->value=='boolean'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('CheckBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleCheckBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'int')){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>10), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='datetime'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('DateTime', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleDateTime, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>10), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'enum')){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('DropDown', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleDropDown, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_items'=>libraryDataBaseChainQueryBuilder::parseEnum($_smarty_tpl->tpl_vars['_type']->value),'_resource'=>'custom','_ownerName'=>$_smarty_tpl->tpl_vars['_ownerName']->value), 0);?>

    <?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleFieldLabel, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_labelKey'=>((string)$_smarty_tpl->tpl_vars['_ownerName']->value)."Field".((string)$_smarty_tpl->tpl_vars['_name']->value),'_resource'=>'custom','_controlName'=>$_smarty_tpl->tpl_vars['_name']->value,'_controlType'=>$_smarty_tpl->tpl_vars['_standartControlType']->value), 0);?>

<?php echo Smarty::$_smarty_vars['capture']['control'];?>


</div><?php }} ?>