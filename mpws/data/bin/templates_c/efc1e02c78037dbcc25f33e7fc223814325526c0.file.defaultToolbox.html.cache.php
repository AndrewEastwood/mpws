<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:51
         compiled from "/var/www/mpws/web/customer/toolbox/template/layout/defaultToolbox.html" */ ?>
<?php /*%%SmartyHeaderCode:702542075507ba5df277520-87354648%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efc1e02c78037dbcc25f33e7fc223814325526c0' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/layout/defaultToolbox.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'f169ec851061427f63366f55936d49966dc76cb1' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1350280496,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '702542075507ba5df277520-87354648',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'OBJECT' => 0,
    'INFO' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5df308a83_97702912',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5df308a83_97702912')) {function content_507ba5df308a83_97702912($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><!DOCTYPE html>
<html>
<head>
    <title>
    <?php echo $_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectProperty_site_title;?>

    <?php echo $_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectProperty_site_titleSeparator;?>

    <?php echo $_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->{"objectProperty_site_page".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE']))};?>

</title>
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <link rel="stylesheet" type="text/css" href="/static/mpwsDefault.css">
    <script type="text/javascript" src="/static/mpwsDefault.js"></script>
    
    
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

<div class="MPWSLayout MPWSLayoutDefaultSystem">

    <?php if ($_smarty_tpl->tpl_vars['INFO']->value['USER']['ACTIVE']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_component_pageDispatcher"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_login, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>

</div>

</body>
</html><?php }} ?>