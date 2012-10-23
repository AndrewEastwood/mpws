<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 11:11:09
         compiled from "/var/www/mpws/web/default/v1.0/template/control/htmlTextArea.html" */ ?>
<?php /*%%SmartyHeaderCode:16756942905081704bb6daf9-55406046%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '44e1e4a41f3888ebb58b0387ec68f35e9ce4fd4b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlTextArea.html',
      1 => 1350979866,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16756942905081704bb6daf9-55406046',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5081704bcf5c32_21475122',
  'variables' => 
  array (
    '_name' => 0,
    '_controlCssName' => 0,
    '_value' => 0,
    '_cols' => 0,
    '_rows' => 0,
    '_renderMode' => 0,
    '_controlCssNameCustom' => 0,
    '_controlRenderMode' => 0,
    '_controlName' => 0,
    '_controlColSize' => 0,
    '_controlRowSize' => 0,
    '_controlValue' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5081704bcf5c32_21475122')) {function content_5081704bcf5c32_21475122($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlName'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('TextArea', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_value']->value)===null||$tmp==='' ? libraryRequest::getPostFormField(mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')) : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlColSize'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_cols']->value)===null||$tmp==='' ? 45 : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRowSize'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_rows']->value)===null||$tmp==='' ? 6 : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_renderMode']->value)===null||$tmp==='' ? 'normal' : $tmp), null, 0);?>


<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
 MPWSControlRenderMode<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_controlRenderMode']->value);?>
">
<?php if ($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='normal'||$_smarty_tpl->tpl_vars['_controlRenderMode']->value=='error'){?>
    
    
    <textarea id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" cols="<?php echo $_smarty_tpl->tpl_vars['_controlColSize']->value;?>
" rows="<?php echo $_smarty_tpl->tpl_vars['_controlRowSize']->value;?>
" class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
</textarea>
           
<?php }elseif($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='hidden'){?>
    
    
    <span class="MPWSControlReadOnlyValue"><?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
</span>
    <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
"/>
         
<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Wrong control render mode",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?>
</div><?php }} ?>