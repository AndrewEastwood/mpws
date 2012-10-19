<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:16:40
         compiled from "/var/www/mpws/web/default/v1.0/template/trigger/control.html" */ ?>
<?php /*%%SmartyHeaderCode:438248167508152b8ceeb48-32731021%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a291fbfdf58a8c4b968a9b89fa85536efefe03b7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/trigger/control.html',
      1 => 1350652162,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '438248167508152b8ceeb48-32731021',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_renderMode' => 0,
    '_value' => 0,
    '_resource' => 0,
    '_type' => 0,
    'CURRENT' => 0,
    '_name' => 0,
    '_controlValue' => 0,
    '_ownerName' => 0,
    '_standartControlType' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508152b8e58160_63912644',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508152b8e58160_63912644')) {function content_508152b8e58160_63912644($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>

<?php $_smarty_tpl->tpl_vars['_renderMode'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_renderMode']->value)===null||$tmp==='' ? 'normal' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_value']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_res'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_resource']->value)===null||$tmp==='' ? 'component' : $tmp), null, 0);?>
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderMode<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_renderMode']->value);?>
">

<?php $_smarty_tpl->_capture_stack[0][] = array("control", null, null); ob_start(); ?>
    <?php if ($_smarty_tpl->tpl_vars['_type']->value=='text'||$_smarty_tpl->tpl_vars['_type']->value=='textarea'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextArea', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlTextArea, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'varchar')||$_smarty_tpl->tpl_vars['_type']->value=='textbox'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>glGetOnlyNums($_smarty_tpl->tpl_vars['_type']->value),'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='tinyint(1)'||$_smarty_tpl->tpl_vars['_type']->value=='bool'||$_smarty_tpl->tpl_vars['_type']->value=='boolean'||$_smarty_tpl->tpl_vars['_type']->value=='checkbox'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('CheckBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlCheckBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'int')){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('TextBox', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlTextBox, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>10,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='datetime'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('DateTime', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlDateTime, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_limit'=>10,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif($_smarty_tpl->tpl_vars['_type']->value=='password'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('Password', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlPassword, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }elseif(startsWith($_smarty_tpl->tpl_vars['_type']->value,'enum')||$_smarty_tpl->tpl_vars['_type']->value=='select'){?>
        <?php $_smarty_tpl->tpl_vars['_standartControlType'] = new Smarty_variable('DropDown', null, 0);?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlDropDown, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_items'=>libraryDataBaseChainQueryBuilder::parseEnum($_smarty_tpl->tpl_vars['_type']->value),'_resource'=>'custom','_ownerName'=>$_smarty_tpl->tpl_vars['_ownerName']->value,'_renderMode'=>$_smarty_tpl->tpl_vars['_renderMode']->value,'_value'=>$_smarty_tpl->tpl_vars['_controlValue']->value), 0);?>

    <?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_label, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>((string)$_smarty_tpl->tpl_vars['_ownerName']->value)."Field".((string)$_smarty_tpl->tpl_vars['_name']->value),'_controlOwner'=>$_smarty_tpl->tpl_vars['_name']->value+$_smarty_tpl->tpl_vars['_standartControlType']->value), 0);?>

<?php echo Smarty::$_smarty_vars['capture']['control'];?>


</div><?php }} ?>