<?php /* Smarty version Smarty-3.1.11, created on 2012-09-20 02:02:47
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/templates/layout/page.html" */ ?>
<?php /*%%SmartyHeaderCode:996130154505798fa017cf1-46267550%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4391ea20016dbc0433021798afdbfc00fd70838d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/templates/layout/page.html',
      1 => 1348095766,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '996130154505798fa017cf1-46267550',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505798fa1542a1_66576674',
  'variables' => 
  array (
    'MODEL' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505798fa1542a1_66576674')) {function content_505798fa1542a1_66576674($_smarty_tpl) {?><!DOCTYPE html>
<html>
    <head>
        <title>Default Page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>
            ------
            <?php echo $_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']['DEMO2']['HTML'];?>

            ------
            
        </div>
    </body>
</html>
<?php }} ?>