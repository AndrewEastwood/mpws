<?php /* Smarty version Smarty-3.1.11, created on 2012-09-12 03:11:04
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/templates/page/test.html" */ ?>
<?php /*%%SmartyHeaderCode:1278538729504fd318bf1889-23586382%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa21b7271d301f19682b32a405d31e343ad4cae5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/templates/page/test.html',
      1 => 1347408529,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1278538729504fd318bf1889-23586382',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'model' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_504fd318bff642_26506396',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_504fd318bff642_26506396')) {function content_504fd318bff642_26506396($_smarty_tpl) {?><!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>
            <?php echo $_smarty_tpl->tpl_vars['model']->value['widgets']['TOOLBOXMENU']['HTML'];?>

            <?php echo $_smarty_tpl->tpl_vars['model']->value['widgets']['TOOLBOXMENU2']['HTML'];?>

            <?php echo Smarty::$_smarty_vars['capture']['mymsg'];?>

        </div>
    </body>
</html>
<?php }} ?>