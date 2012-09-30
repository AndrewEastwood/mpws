<?php /* Smarty version Smarty-3.1.11, created on 2012-09-30 23:47:17
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/default.html" */ ?>
<?php /*%%SmartyHeaderCode:7250735635068afd57b9792-42767894%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '71e80ae3457b9976bcb9391c6df0b85fa2e8a555' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/default.html',
      1 => 1349021406,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7250735635068afd57b9792-42767894',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'SITE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5068afd5810737_66974109',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5068afd5810737_66974109')) {function content_5068afd5810737_66974109($_smarty_tpl) {?><!DOCTYPE html>
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
    
    <meta name="locale" content="<?php echo $_smarty_tpl->tpl_vars['SITE']->value->getObjectLocale();?>
">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

    <?php if ($_smarty_tpl->tpl_vars['INFO']->value['USER']['ACTIVE']){?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->{"objectTemplatePath_component_pageDispatcher"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['SITE']->value->objectTemplatePath_page_login, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>

</div>

</body>
</html>
<?php }} ?>