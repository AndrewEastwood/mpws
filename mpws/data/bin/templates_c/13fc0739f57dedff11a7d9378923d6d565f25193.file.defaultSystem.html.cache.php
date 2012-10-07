<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 14:59:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html" */ ?>
<?php /*%%SmartyHeaderCode:13723699650716ea2589fe0-63295964%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1349291075,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13723699650716ea2589fe0-63295964',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50716ea25bc2a2_19355516',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50716ea25bc2a2_19355516')) {function content_50716ea25bc2a2_19355516($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'];?>
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
        mpws.page = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'];?>
';
        // display
        mpws.display = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'];?>
';
        // action
        mpws.action = '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['ACTION'];?>
';
    </script>
    
    <meta name="locale" content="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->getObjectLocale();?>
">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

    <?php if ($_smarty_tpl->tpl_vars['INFO']->value['USER']['ACTIVE']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_component_pageDispatcher"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_login, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>

</div>

</body>
</html><?php }} ?>