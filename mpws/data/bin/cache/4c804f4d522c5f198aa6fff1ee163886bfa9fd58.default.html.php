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
    '3551f328059be67d356ac7288a98dc9ca4b897bf' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/login.html',
      1 => 1348424253,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '681098806505de7684c3611-91588357',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505f852c35c493_51989900',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505f852c35c493_51989900')) {function content_505f852c35c493_51989900($_smarty_tpl) {?><!DOCTYPE html>
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
    
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

            ; this is an INI file
[section]
name = value

    
</div>

</body>
</html>
<?php }} ?>