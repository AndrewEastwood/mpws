<?php /*%%SmartyHeaderCode:484340775506b40efad05a6-83925282%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1349021406,
      2 => 'file',
    ),
    '62e33b07ec504086a62347b14b2fbbdeaa4083e5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageDispatcher.html',
      1 => 1349199893,
      2 => 'file',
    ),
    '6a2bd7a905030f8591c53d6372450316d46fb9b7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/tools.html',
      1 => 1349206433,
      2 => 'file',
    ),
    'd6d46df5db445da56cb54532472df58a57c03f2a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/system.html',
      1 => 1349206797,
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
    '04f084c1373abb6a23ce948bf8979bfc88e76392' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkMenu.html',
      1 => 1349205357,
      2 => 'file',
    ),
    'd0b60fd847c51d05aceafa98d1fbb45732576e1e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html',
      1 => 1349205378,
      2 => 'file',
    ),
    'fee379ac26415e5d616f64f7c05de0bc03ae5909' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartSystemPageStyle1.html',
      1 => 1349205731,
      2 => 'file',
    ),
    'b2768aa8e09878d7f6f1826a659bc1072bf3fe70' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menuPlugins.html',
      1 => 1349207626,
      2 => 'file',
    ),
    'fd493ce1d9a8de3661d7b6fa94771c833d559aee' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPublicPageStyle1.html',
      1 => 1349205658,
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
      1 => 1349200923,
      2 => 'file',
    ),
    '531a0bfa0ec2c65a065a6298b73205faca29dadf' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataElements.html',
      1 => 1349022504,
      2 => 'file',
    ),
    '663f554cd257e4d3947bc3ec192716b71147a8a4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/footer.html',
      1 => 1349205814,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '484340775506b40efad05a6-83925282',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506b46d449e963_24857774',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506b46d449e963_24857774')) {function content_506b46d449e963_24857774($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - tools</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = '4a931512ce65bdc9ca6808adf92d8783';
        // page
        mpws.page = 'tools';
        // display
        mpws.display = '';
        // action
        mpws.action = 'default';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

            





    
                
            





 

<div class="MPWSPage MPWSPageDisplay MPWSPageStandartPublicPageStyle1 MPWSPageStandartPublic" id="MPWSPageStandartPublicToolsID">
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
            <span class="MPWSValue">2012-10-02 20:52:55</span>
        </div>
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Your home page link is:</span>
            <span class="MPWSValue"><a href="http://www.google.com" target="blank" class="MPWSLink">toolbox</a></span>
        </div>
        <button type="submit" name="do" value="logout" class="MPWSButton">Logout</button>
    </form>
</div>
                        

<div class="MPWSComponent MPWSComponenMenu">
        <ul class="MPWSList MPWSListMenu">
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="index.html" target="_self" title="Home Page">
                <span>Home Page</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="dashboard.html" target="_self" title="Dashboard">
                <span>Dashboard</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="tools.html" target="_self" title="Tools">
                <span>Tools</span>
                            </a>
                            <ul class="MPWSList MPWSListPluginLinks">
    <li class="MPWSListItem MPWSListItemPluginLink">
        <a href="/page/tools.html?plugin=toolbox" title="Toolbox">
            <span class="MPWSText MPWSTextTitle">Toolbox</span>
                                            

<div class="MPWSComponent MPWSComponenMenu">
        <ul class="MPWSList MPWSListMenu">
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="tools.html?plugin=toolbox&display=users" target="_self" title="User Manager">
                <span>User Manager</span>
                            </a>
                    </li>
            </ul>
    </div>
                    </a>
    </li>
</ul>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="users.html" target="_self" title="Users">
                <span>Users</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="messages.html" target="_self" title="Web Messages">
                <span>Web Messages</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="help.html" target="_self" title="Help">
                <span>Help</span>
                            </a>
                    </li>
            </ul>
    </div>
            </div>
    <div class="MPWSComponent MPWSComponenContent">
    <div class="MPWSComponent MPWSComponentDataElements">
    
                        <div class="MPWSBlock">            <div id="MPWSComponentMessageCommonID" class="MPWSComponent MPWSComponentMessage MPWSComponentMessageCommon">
        <ul>
                    <li class="MPWSMessage">Hello World!!!!!</li>
                </ul>
        </div>
    </div>
                    <div class="MPWSBlock"><div id="MPWSWidgetSystemPluginLinkMenuID" class="MPWSWidget MPWSWidgetSystemPluginLinkMenu">

        
    
<div class="MPWSComponent MPWSComponenMenu">
        <ul class="MPWSList MPWSListMenu">
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="tools.html?plugin=toolbox&display=users" target="_self" title="User Manager">
                <span>User Manager</span>
                                <span>Manage System Users</span>
                            </a>
                    </li>
            </ul>
    </div>

</div></div>
            </div>

</div>
    <div class="MPWSComponent MPWSComponenFooter">
    
    </div>
</div>
    
</div>

</body>
</html>
<?php }} ?>