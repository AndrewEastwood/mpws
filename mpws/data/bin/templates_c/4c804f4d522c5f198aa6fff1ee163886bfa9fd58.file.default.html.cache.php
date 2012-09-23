<?php /* Smarty version Smarty-3.1.11, created on 2012-09-24 00:54:36
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/default.html" */ ?>
<?php /*%%SmartyHeaderCode:681098806505de7684c3611-91588357%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4c804f4d522c5f198aa6fff1ee163886bfa9fd58' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/default.html',
      1 => 1348437272,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '681098806505de7684c3611-91588357',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505de7684f5787_39913365',
  'variables' => 
  array (
    'INFO' => 0,
    'SITE' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505de7684f5787_39913365')) {function content_505de7684f5787_39913365($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - <?php echo $_smarty_tpl->tpl_vars['INFO']->value['PAGE'];?>
</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['TOKEN'];?>
';
        // page
        mpws.page = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['PAGE'];?>
';
        // display
        mpws.display = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['DISPLAY'];?>
';
        // action
        mpws.action = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['ACTION'];?>
';
    </script>
    
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

    <?php if ($_smarty_tpl->tpl_vars['INFO']->value['USER']['STATE']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_page_login, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_page_normal, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>

</div>

</body>
</html>
<?php }} ?>