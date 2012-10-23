<?php /* Smarty version Smarty-3.1.11, created on 2012-10-23 20:33:07
         compiled from "/var/www/mpws/web/default/v1.0/template/component/title.html" */ ?>
<?php /*%%SmartyHeaderCode:134981273750816ed39aac74-00346822%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a0ddab4075dc8e4fdcb58fc91caa9a6edf3b90dc' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/title.html',
      1 => 1351013580,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '134981273750816ed39aac74-00346822',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50816ed39d7676_56364725',
  'variables' => 
  array (
    'CURRENT' => 0,
    'OBJECT' => 0,
    '_resourceOwner' => 0,
    '__prop__' => 0,
    'DISPLAY_OBJECT' => 0,
    '_customText' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50816ed39d7676_56364725')) {function content_50816ed39d7676_56364725($_smarty_tpl) {?>


<?php $_smarty_tpl->tpl_vars["DISPLAY_OBJECT"] = new Smarty_variable(glGetFirstNonEmptyValue($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT'],$_smarty_tpl->tpl_vars['OBJECT']->value['SITE']), null, 0);?>


<?php $_smarty_tpl->tpl_vars["__prop__"] = new Smarty_variable("objectProperty_".((string)(($tmp = @$_smarty_tpl->tpl_vars['_resourceOwner']->value)===null||$tmp==='' ? 'component' : $tmp))."_", null, 0);?>

<div class="MPWSComponent MPWSComponentTitle">
    <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."Title"};?>
</span>
    <span class="MPWSText MPWSTextDetails"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{((string)$_smarty_tpl->tpl_vars['__prop__']->value)."Description"};?>
</span>
    <?php if (isset($_smarty_tpl->tpl_vars['_customText']->value)){?>
    <span class="MPWSText MPWSTextCustom"><?php echo $_smarty_tpl->tpl_vars['_customText']->value;?>
</span>
    <?php }?>
</div><?php }} ?>