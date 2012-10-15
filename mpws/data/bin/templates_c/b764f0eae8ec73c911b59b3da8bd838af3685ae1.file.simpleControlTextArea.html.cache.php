<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 10:12:19
         compiled from "/var/www/mpws/web/default/v1.0/template/component/simpleControlTextArea.html" */ ?>
<?php /*%%SmartyHeaderCode:1164766033507bb7531d74d5-83540626%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b764f0eae8ec73c911b59b3da8bd838af3685ae1' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlTextArea.html',
      1 => 1350280496,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1164766033507bb7531d74d5-83540626',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_name' => 0,
    '_controlCssName' => 0,
    '_controlName' => 0,
    '_renderMode' => 0,
    '_controlValue' => 0,
    '_value' => 0,
    '_cols' => 0,
    '_rows' => 0,
    '_controlCssNameCustom' => 0,
    '_controlRenderMode' => 0,
    '_controlColSize' => 0,
    '_controlRowSize' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507bb753278000_12129386',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507bb753278000_12129386')) {function content_507bb753278000_12129386($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlName'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('TextArea', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(libraryRequest::getPostValue($_smarty_tpl->tpl_vars['_controlName']->value,false), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlColSize'] = new Smarty_variable(45, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRowSize'] = new Smarty_variable(6, null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable('normal', null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_renderMode']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable($_smarty_tpl->tpl_vars['_renderMode']->value, null, 0);?>
<?php }?>
<?php if (empty($_smarty_tpl->tpl_vars['_controlValue']->value)&&isset($_smarty_tpl->tpl_vars['_value']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_cols']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlColSize'] = new Smarty_variable($_smarty_tpl->tpl_vars['_cols']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_rows']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlRowSize'] = new Smarty_variable($_smarty_tpl->tpl_vars['_rows']->value, null, 0);?>
<?php }?>

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