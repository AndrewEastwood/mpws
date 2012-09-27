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
      1 => 1348777729,
      2 => 'file',
    ),
    '69efce9731aeeb54451ddefc0c66f864c1ae09b4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1348684566,
      2 => 'file',
    ),
    '4b6907d3d72d2c63144539dc3dd7241049b78f4d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/message.html',
      1 => 1348605107,
      2 => 'file',
    ),
    '715881089a1e723006cb411bae2d83f09cb7183a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standart.html',
      1 => 1348684321,
      2 => 'file',
    ),
    'a297d65371600e951f5bf16207ed21a2b5fd17a0' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/header.html',
      1 => 1348686514,
      2 => 'file',
    ),
    '0acc49945e64837379fb78db2aad4b0b21c1615a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html',
      1 => 1348684890,
      2 => 'file',
    ),
    '7b07e181672f9c889f7904c12e7d7d4d8f52d21a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/content.html',
      1 => 1348768924,
      2 => 'file',
    ),
    'd0b60fd847c51d05aceafa98d1fbb45732576e1e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html',
      1 => 1348769917,
      2 => 'file',
    ),
    '663f554cd257e4d3947bc3ec192716b71147a8a4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/footer.html',
      1 => 1348767794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2362038085062240e8c91d5-65005751',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5064bd8e4993a5_24852603',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5064bd8e4993a5_24852603')) {function content_5064bd8e4993a5_24852603($_smarty_tpl) {?><!DOCTYPE html>
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

            

<div class="MPWSPage MPWSPageStandart">
    <div class="MPWSComponent MPWSComponenHeader">
    <div class="MPWSComponent MPWSComponentLogo">
    <a href="http://www.google.com" target="blank" class="MPWSLink">
        <img src="/static/toolbox_logo.gif" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div>
    
                        <div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">You are signed in as:</span>
            <span class="MPWSValue">test3</span>
        </div><div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Last access:</span>
            <span class="MPWSValue">2012-09-27 22:17:42</span>
        </div>
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Your home page link is:</span>
            <span class="MPWSValue"><a href="http://www.google.com" target="blank" class="MPWSLink">toolbox</a></span>
        </div>
        <button type="submit" name="do" value="logout" class="MPWSButton">Logout</button>
    </form>
</div>
            </div>
    <div class="MPWSComponent MPWSComponenContent">
    <div class="MPWSComponent MPWSComponenMenu">
        <ul class="MPWSList MPWSListMenu">
            <li class="MPWSListItem MPWSListItemMenu">
            <a href="index.html" target="_self" title="">Home Page</a>
        </li>
            <li class="MPWSListItem MPWSListItemMenu">
            <a href="dashboard.html" target="_self" title="">Dashboard</a>
        </li>
            <li class="MPWSListItem MPWSListItemMenu">
            <a href="tools.html" target="_self" title="">Tools</a>
        </li>
            <li class="MPWSListItem MPWSListItemMenu">
            <a href="users.html" target="_self" title="">Users</a>
        </li>
            <li class="MPWSListItem MPWSListItemMenu">
            <a href="messages.html" target="_self" title="">Web Messages</a>
        </li>
            <li class="MPWSListItem MPWSListItemMenu">
            <a href="help.html" target="_self" title="">Help</a>
        </li>
            </ul>
    </div>
    
                                    <div id="MPWSComponentMessageCommonID" class="MPWSComponent MPWSComponentMessage MPWSComponentMessageCommon">
        <ul>
                    <li class="MPWSMessage">Hello World!!!!!</li>
                </ul>
        </div>
    
            </div>
    <div class="MPWSComponent MPWSComponenFooter">
    
                </div>
</div>
    
    
    

    
    
    qwertyuiop

    
</div>

</body>
</html>
<?php }} ?>