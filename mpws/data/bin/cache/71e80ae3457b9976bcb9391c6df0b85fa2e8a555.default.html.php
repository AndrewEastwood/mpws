<?php /*%%SmartyHeaderCode:2362038085062240e8c91d5-65005751%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '71e80ae3457b9976bcb9391c6df0b85fa2e8a555' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/default.html',
      1 => 1348927336,
      2 => 'file',
    ),
    '48a01374c6045ce1f4ded1f4efca0a227fdf65db' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html',
      1 => 1348952433,
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
    'f8c681a28bc173b0a8089654f24b353930927eca' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/index.html',
      1 => 1348928927,
      2 => 'file',
    ),
    '556bc64bdc470a6a79b21c45d2f6bc4ce60d71ce' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPageStyle1.html',
      1 => 1348928114,
      2 => 'file',
    ),
    'a297d65371600e951f5bf16207ed21a2b5fd17a0' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/header.html',
      1 => 1348924036,
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
      1 => 1348952232,
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
  'unifunc' => 'content_506762bbd79174_31539161',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506762bbd79174_31539161')) {function content_506762bbd79174_31539161($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - dashboard</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = 'dc7161be3dbf2250c8954e560cc35060';
        // page
        mpws.page = 'dashboard';
        // display
        mpws.display = '';
        // action
        mpws.action = 'default';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

            


<div class="MPWSPage MPWSPageStandartPageStyle1 MPWSPageStandart" id="MPWSPageStandartDashboardID">
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
            <span class="MPWSValue">2012-09-29 17:48:46</span>
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
    
                    <b>ACTIVE USERS</b>
<div class="MPWSDivTable">
    <div class="MPWSDivTableRow">
            <div class="MPWSDivTableCell">1</div>
            <div class="MPWSDivTableCell">TestUser</div>
            <div class="MPWSDivTableCell">fe01ce2a7fbac8fafaed7c982a04e229</div>
            <div class="MPWSDivTableCell">1</div>
            <div class="MPWSDivTableCell">0</div>
            <div class="MPWSDivTableCell">Toolbox:*:all;
Writer:*:all;</div>
            <div class="MPWSDivTableCell">2012-06-26 00:00:00</div>
            <div class="MPWSDivTableCell">2012-06-26 00:00:00</div>
        </div>
    <div class="MPWSDivTableRow">
            <div class="MPWSDivTableCell">2</div>
            <div class="MPWSDivTableCell">olololo</div>
            <div class="MPWSDivTableCell">fe01ce2a7fbac8fafaed7c982a04e229</div>
            <div class="MPWSDivTableCell">1</div>
            <div class="MPWSDivTableCell">0</div>
            <div class="MPWSDivTableCell"></div>
            <div class="MPWSDivTableCell">0000-00-00 00:00:00</div>
            <div class="MPWSDivTableCell">0000-00-00 00:00:00</div>
        </div>
    <div class="MPWSDivTableRow">
            <div class="MPWSDivTableCell">3</div>
            <div class="MPWSDivTableCell">test3</div>
            <div class="MPWSDivTableCell">fe01ce2a7fbac8fafaed7c982a04e229</div>
            <div class="MPWSDivTableCell">1</div>
            <div class="MPWSDivTableCell">1</div>
            <div class="MPWSDivTableCell"></div>
            <div class="MPWSDivTableCell">2012-09-29 23:43:36</div>
            <div class="MPWSDivTableCell">2012-06-25 23:56:20</div>
        </div>
</div>

            </div>
    <div class="MPWSComponent MPWSComponenFooter">
    
    </div>
</div>
    
</div>

</body>
</html>
<?php }} ?>