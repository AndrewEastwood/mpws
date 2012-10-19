<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:51
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/defaultToolbox.html" */ ?>
<?php /*%%SmartyHeaderCode:7814884605080405b233f60-61888913%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '784a3f42e7f6316dff3b60d20ea358e590c716ce' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/defaultToolbox.html',
      1 => 1350582441,
      2 => 'file',
    ),
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7814884605080405b233f60-61888913',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5080405b2d24d6_35319370',
  'variables' => 
  array (
    'OBJECT' => 0,
    'INFO' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5080405b2d24d6_35319370')) {function content_5080405b2d24d6_35319370($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
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
    <link rel="stylesheet" type="text/css" href="/static/mpwsCustomer.css">

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
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_trigger_page"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }else{ ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_login, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>
</div>

</body>
</html><?php }} ?>