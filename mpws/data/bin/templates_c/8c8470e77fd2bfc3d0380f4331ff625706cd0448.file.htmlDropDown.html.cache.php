<?php /* Smarty version Smarty-3.1.11, created on 2012-10-24 09:47:29
         compiled from "/var/www/mpws/web/default/v1.0/template/control/htmlDropDown.html" */ ?>
<?php /*%%SmartyHeaderCode:1019307532508170658484f8-14287635%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c8470e77fd2bfc3d0380f4331ff625706cd0448' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlDropDown.html',
      1 => 1351061246,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1019307532508170658484f8-14287635',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5081706593ab51_24261808',
  'variables' => 
  array (
    '_name' => 0,
    '_controlCssName' => 0,
    '_value' => 0,
    '_renderMode' => 0,
    '_items' => 0,
    '_controlCssNameCustom' => 0,
    '_controlRenderMode' => 0,
    '_controlName' => 0,
    '_controlItems' => 0,
    '_controlValue' => 0,
    '_item' => 0,
    '_resourceOwner' => 0,
    '_controlOwner' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5081706593ab51_24261808')) {function content_5081706593ab51_24261808($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlName'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('DropDown', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlItems'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_value']->value)===null||$tmp==='' ? libraryRequest::getPostFormField(mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')) : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_renderMode']->value)===null||$tmp==='' ? 'normal' : $tmp), null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_items']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlItems'] = new Smarty_variable($_smarty_tpl->tpl_vars['_items']->value, null, 0);?>
<?php }?>

<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
 MPWSControlRenderMode<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_controlRenderMode']->value);?>
">
    
<?php if ($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='normal'||$_smarty_tpl->tpl_vars['_controlRenderMode']->value=='error'){?>
    
    
    
    <select id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
        <?php  $_smarty_tpl->tpl_vars['_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_controlItems']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_item']->key => $_smarty_tpl->tpl_vars['_item']->value){
$_smarty_tpl->tpl_vars['_item']->_loop = true;
?>
            <?php if ($_smarty_tpl->tpl_vars['_controlValue']->value==$_smarty_tpl->tpl_vars['_item']->value){?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['_item']->value;?>
" selected="selected">
            <?php }else{ ?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['_item']->value;?>
">
            <?php }?>
                <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_option".((string)$_smarty_tpl->tpl_vars['_controlOwner']->value).((string)$_smarty_tpl->tpl_vars['_controlCssNameCustom']->value).((string)$_smarty_tpl->tpl_vars['_item']->value)};?>

            </option>
        <?php } ?>
    </select>
           
<?php }elseif($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='hidden'){?>
    
    
    <span class="MPWSControlReadOnlyValue"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resourceOwner']->value)."_option".((string)$_smarty_tpl->tpl_vars['_controlOwner']->value).((string)$_smarty_tpl->tpl_vars['_controlCssNameCustom']->value).((string)$_smarty_tpl->tpl_vars['_controlValue']->value)};?>
</span>
    <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
">

<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Wrong control render mode",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?>

</div><?php }} ?>