<?php /* Smarty version Smarty-3.1.11, created on 2012-09-22 19:29:28
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/default.html" */ ?>
<?php /*%%SmartyHeaderCode:681098806505de7684c3611-91588357%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4c804f4d522c5f198aa6fff1ee163886bfa9fd58' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/default.html',
      1 => 1348331367,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '681098806505de7684c3611-91588357',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505de7684f5787_39913365',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505de7684f5787_39913365')) {function content_505de7684f5787_39913365($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>

    
</head>
<body>


<div class="MPWSPage MPWSPageToolbox">

<div class="MPWSPage MPWSPageToolbox MPWSPageDisplay_Login">

    <div class="MPWSWrapper MPWSWrapperLayoutToolbox">
        <div class="MPWSLayout MPWSLayoutToolbox">
            <h2>TEAM LEAD WORKDESK</h2>
            <div class="MPWSComponent MPWSComponentUserInfo">
                <form action="" method="POST">
                    You are signed in as: <b>username</b>
                    <input type="submit" name="do" value="logout"/>
                </form>
                Your home page url: <a href="//<?php echo $_SERVER['HTTP_HOST'];?>
" target="_blank"><?php echo @MPWS_CUSTOMER;?>
</a>
                <div>
                    tools
                </div>
            </div>
            
            <hr size="2">
            
                <div class="MPWSComponent MPWSComponentMenu">
                    menu
                </div>
            <div class="MPWSComponent MPWSComponentContent">
                messages
                <div class="MPWSMainContentWrapper">
                content
                <p class="MPWSEmptyParagraph">&nbsp</p>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
<?php }} ?>