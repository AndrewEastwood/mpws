<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 22:57:24
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/controlFieldSwitcher.html" */ ?>
<?php /*%%SmartyHeaderCode:2111954693507c64fbd9c336-51753530%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bb64dcf15800472279be847786c5bb40578668b9' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/controlFieldSwitcher.html',
      1 => 1350331034,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2111954693507c64fbd9c336-51753530',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507c64fbe61ec2_14809709',
  'variables' => 
  array (
    '_readonly' => 0,
    '_render' => 0,
    '_value' => 0,
    '_renderMode' => 0,
    '_type' => 0,
    'CURRENT' => 0,
    '_name' => 0,
    '_controlValue' => 0,
    '_ownerName' => 0,
    '_standartControlType' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507c64fbe61ec2_14809709')) {function content_507c64fbe61ec2_14809709($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>

<?php $_smarty_tpl->tpl_vars['_renderMode'] = new Smarty_variable('normal', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(false, null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['_readonly']->value)&&$_smarty_tpl->tpl_vars['_readonly']->value){?>
    <?php $_smarty_tpl->tpl_vars['_renderMode'] = new Smarty_variable('hidden', null, 0);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['_render']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_renderMode'] = new Smarty_variable($_smarty_tpl->tpl_vars['_render']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_value']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>
<?php }?>
    
<div class="MPWSComponent MPWSComponentField MPWSRenderMode<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_renderMode']->value);?>
">

<?php $_smarty_tpl->_capture_stack[0][] = array("control", null, null); ob_start(); ?>
    <?php if ($_smarty_tpl->tpl_vars['_type']->value=='text'||$_smarty_tpl->tpl_vars['_type']->value=='textarea'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextArea', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleControlTextArea, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'varchar')||$_smarty_tpl->tpl_vars['_type']->value=='textbox'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleControlTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>glGetOnlyNums($_smarty_tpl->tpl_vars['_type']->value),'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='tinyint(1)'||$_smarty_tpl->tpl_vars['_type']->value=='bool'||$_smarty_tpl->tpl_vars['_type']->value=='boolean'||$_smarty_tpl->tpl_vars['_type']->value=='checkbox'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('CheckBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleControlCheckBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'int')){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleControlTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>10,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='datetime'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('DateTime', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleControlDateTime, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>10,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'enum')||$_smarty_tpl->tpl_vars['_type']->value=='select'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('DropDown', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleControlDropDown, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_items'=>libraryDataBaseChainQueryBuilder::parseEnum($_smarty_tpl->tpl_vars['_type']->value),'_resource'=>'custom','_ownerName'=>$_smarty_tpl->tpl_vars['_ownerName']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

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