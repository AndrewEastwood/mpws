<?php /*%%SmartyHeaderCode:2362038085062240e8c91d5-65005751%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '71e80ae3457b9976bcb9391c6df0b85fa2e8a555' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/default.html',
      1 => 1348609128,
      2 => 'file',
    ),
    'abf8edb63cc6678831be8cf3eb1498e6961e2517' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/toolbox.html',
      1 => 1348608471,
      2 => 'file',
    ),
    '69efce9731aeeb54451ddefc0c66f864c1ae09b4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1348610218,
      2 => 'file',
    ),
    '4b6907d3d72d2c63144539dc3dd7241049b78f4d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/message.html',
      1 => 1348605107,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2362038085062240e8c91d5-65005751',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50622933e3cdf4_72784192',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50622933e3cdf4_72784192')) {function content_50622933e3cdf4_72784192($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - index</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = '6a992d5529f459a44fee58c733255e86';
        // page
        mpws.page = 'index';
        // display
        mpws.display = '';
        // action
        mpws.action = 'default';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

            






<div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="?" method="POST">
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">You are signed in as:</span>
            <span class="MPWSValue">test3</span>
        </div><div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Last access:</span>
            <span class="MPWSValue">2012-09-26 00:57:30</span>
        </div>
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Your home page link is:</span>
            <span class="MPWSValue"><a href="http://www.google.com" target="blank">toolbox</a></span>
        </div>
        <button type="submit" name="do" value="logout">Logout</button>
    </form>
</div>
TOOLBOX CONTENT
            <div id="MPWSComponentMessageCommonID" class="MPWSComponent MPWSComponentMessage MPWSComponentMessageCommon">
        <ul>
                    <li class="MPWSMessage">Hello World!!!!!</li>
                </ul>
        </div>
    
    
</div>

</body>
</html>
<?php }} ?>