<?php /*%%SmartyHeaderCode:1892192525507aee7b632789-30698144%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '784a3f42e7f6316dff3b60d20ea358e590c716ce' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/defaultToolbox.html',
      1 => 1350327224,
      2 => 'file',
    ),
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1350327225,
      2 => 'file',
    ),
    'e517ca49fa332e743f403d0a781c0ae4f2ba9a18' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/login.html',
      1 => 1350327225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1892192525507aee7b632789-30698144',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507d98917c3880_48196269',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507d98917c3880_48196269')) {function content_507d98917c3880_48196269($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>
    Toolbox
     - 
    Tools
</title>
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <link rel="stylesheet" type="text/css" href="/static/mpwsDefault.css">
    <script type="text/javascript" src="/static/mpwsDefault.js"></script>
    
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = '4a931512ce65bdc9ca6808adf92d8783';
        // page
        mpws.page = 'tools';
        // display
        mpws.display = 'users';
        // action
        mpws.action = 'default';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutDefaultSystem">

            <div class="MPWSPage MPWSPageLogin">

<form action="?plugin=toolbox&display=users" method="POST">
    <p>
    <input type="text" name="mpws_user_login" value="" placeholder="Login"/>
    </p>
    <p>
    <input type="password" name="mpws_user_pwd" value="" placeholder="Password"/>
    </p>
    <p>
    <input type="submit" name="do" value="SignIn"/>
    </p>
</form>

</div>
    
</div>

</body>
</html><?php }} ?>