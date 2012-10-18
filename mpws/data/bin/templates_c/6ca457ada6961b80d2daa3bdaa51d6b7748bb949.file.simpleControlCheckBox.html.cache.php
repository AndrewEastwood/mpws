<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 21:03:31
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlCheckBox.html" */ ?>
<?php /*%%SmartyHeaderCode:78719055350804473d798c1-13209995%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6ca457ada6961b80d2daa3bdaa51d6b7748bb949' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlCheckBox.html',
      1 => 1350327225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '78719055350804473d798c1-13209995',
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
    '_controlCssNameCustom' => 0,
    '_controlRenderMode' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50804473e19e75_62154702',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50804473e19e75_62154702')) {function content_50804473e19e75_62154702($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<?php $_smarty_tpl->tpl_vars['_controlName'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8')), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('CheckBox', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssNameCustom'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_controlCssName']->value).((string)$_smarty_tpl->tpl_vars['_name']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable((($tmp = @libraryRequest::getPostValue($_smarty_tpl->tpl_vars['_controlName']->value))===null||$tmp==='' ? "unchecked" : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable('normal', null, 0);?>


<?php if (isset($_smarty_tpl->tpl_vars['_renderMode']->value)){?>
    <?php $_smarty_tpl->tpl_vars['_controlRenderMode'] = new Smarty_variable($_smarty_tpl->tpl_vars['_renderMode']->value, null, 0);?>
<?php }?>
<?php if ((empty($_smarty_tpl->tpl_vars['_controlValue']->value)&&!empty($_smarty_tpl->tpl_vars['_value']->value))||$_smarty_tpl->tpl_vars['_controlValue']->value=="on"||$_smarty_tpl->tpl_vars['_controlValue']->value=="1"){?>
    <?php $_smarty_tpl->tpl_vars['_controlValue'] = new Smarty_variable("checked", null, 0);?>
<?php }?>



<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
 MPWSControlRenderMode<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_controlRenderMode']->value);?>
">

    <?php if ($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='normal'||$_smarty_tpl->tpl_vars['_controlRenderMode']->value=='error'){?>
        
        <?php if ($_smarty_tpl->tpl_vars['_controlValue']->value=="checked"){?>
            <input id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
 class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
        <?php }else{ ?>
            <input id="MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
ID" type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['_controlValue']->value;?>
 class="MPWSControl MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
 MPWSControl<?php echo $_smarty_tpl->tpl_vars['_controlCssNameCustom']->value;?>
">
        <?php }?>
    <?php }elseif($_smarty_tpl->tpl_vars['_controlRenderMode']->value=='hidden'){?>

        
        <?php if ($_smarty_tpl->tpl_vars['_controlValue']->value=="checked"){?>
            <span class="MPWSControlReadOnlyValue">ON</span>
            <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" value="1">
        <?php }else{ ?>
            <span class="MPWSControlReadOnlyValue">OFF</span>
            <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_controlName']->value;?>
" value="0">
        <?php }?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_exception, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_message'=>"Wrong control render mode",'_tpl'=>basename($_smarty_tpl->source->filepath)), 0);?>

    <?php }?>


</div><?php }} ?>