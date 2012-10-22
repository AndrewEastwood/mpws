<?php /* Smarty version Smarty-3.1.11, created on 2012-10-22 22:08:02
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlDropDown.html" */ ?>
<?php /*%%SmartyHeaderCode:133083085450859992f0ff07-87532318%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '139ad89f33c61fbb4479f7ad4755b741d4601107' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlDropDown.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '133083085450859992f0ff07-87532318',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_name' => 0,
    '_controlCssName' => 0,
    '_controlName' => 0,
    '_renderMode' => 0,
    '_items' => 0,
    '_controlValue' => 0,
    '_value' => 0,
    '_controlCssNameCustom' => 0,
    '_controlRenderMode' => 0,
    '_controlItems' => 0,
    '_item' => 0,
    '_resource' => 0,
    '_ownerName' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508599930dc246_19272586',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508599930dc246_19272586')) {function content_508599930dc246_19272586($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlName'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('DropDown', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlItems'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable(libraryRequest::getPostValue($_smarty_tpl->tpl_vars['_controlName']->value,false), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable('normal', null, 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['_renderMode']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable($_smarty_tpl->tpl_vars['_renderMode']->value, null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['_items']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlItems'] = new Smarty_variable($_smarty_tpl->tpl_vars['_items']->value, null, 0);?>
<?php }?>
<?php if ((empty($_smarty_tpl->tpl_vars['_controlValue']->value)&&!empty($_smarty_tpl->tpl_vars['_value']->value))){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable($_smarty_tpl->tpl_vars['_value']->value, null, 0);?>
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
                <?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value).((string)$_smarty_tpl->tpl_vars['_controlCssNameCustom']->value).((string)$_smarty_tpl->tpl_vars['_item']->value)};?>

            </option>
        <?php } ?>
    </select>
           
<?php }elseif($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='hidden'){?>
    
    
    <span class="MPWSControlReadOnlyValue"><?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectProperty_".((string)$_smarty_tpl->tpl_vars['_resource']->value)."_".((string)$_smarty_tpl->tpl_vars['_ownerName']->value).((string)$_smarty_tpl->tpl_vars['_controlCssNameCustom']->value).((string)$_smarty_tpl->tpl_vars['_controlValue']->value)};?>
</span>
    <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
">

<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Wrong control render mode",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

<?php }?>

</div><?php }} ?>