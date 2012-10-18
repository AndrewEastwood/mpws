<?php /*%%SmartyHeaderCode:702542075507ba5df277520-87354648%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efc1e02c78037dbcc25f33e7fc223814325526c0' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/layout/defaultToolbox.html',
      1 => 1350552296,
      2 => 'file',
    ),
    'f169ec851061427f63366f55936d49966dc76cb1' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'f63ea4f5726960dab8f6678067d6aa26dcc52b19' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/login.html',
      1 => 1350538859,
      2 => 'file',
    ),
    '03ea5af42cf2d050632a9f0445ecde83483a5fb5' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/mpwsControlLoginForm.html',
      1 => 1350538859,
      2 => 'file',
    ),
    '3349bdbf34090f0c005554152d092228d9ae2049' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/controlFieldSwitcher.html',
      1 => 1350538859,
      2 => 'file',
    ),
    '895b30db561d11a1f49691acbd28353b8e852c1e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlTextBox.html',
      1 => 1350539220,
      2 => 'file',
    ),
    'f91858edc3c80e6c6d6bf864d185f1e815a16972' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350031670,
      2 => 'file',
    ),
    '9e08f2e38a7b7ac9c07ddd9d59003169d8255ca9' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlPassword.html',
      1 => 1350538859,
      2 => 'file',
    ),
    '903d91440aad38a59f6d2af5f4a037f8c1d06e72' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350484464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '702542075507ba5df277520-87354648',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508019bde9beb3_68736400',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508019bde9beb3_68736400')) {function content_508019bde9beb3_68736400($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>
    Toolbox
     - 
    Tools
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

<div class="MPWSLayout MPWSLayoutDefaultSystem">

            <div class="MPWSPage MPWSPageLogin">
<div class="MPWSControl MPWSControlComplex MPWSControlComplexLoginForm">


<form action="?" method="POST">
    

    
<div class="MPWSComponent MPWSComponentField MPWSRenderModeNormal">


<div class="MPWSFieldLabel MPWSFieldLabelAuthBoxFieldLogin">
    <label for="MPWSControlTextBoxLoginID">
        <span class="MPWSText">User Login</span>
    </label>
</div>
                    

    


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxLogin MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxLoginID" type="text" name="mpws_field_login" value="" size="25" maxlength="" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxLogin">
    
    
</div>
    

</div>
    

        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeNormal">


<div class="MPWSFieldLabel MPWSFieldLabelAuthBoxFieldPassword">
    <label for="MPWSControlPasswordPasswordID">
        <span class="MPWSText">Password</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxPassword MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxPasswordID" type="password" name="mpws_field_password" value="" size="25" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxPassword">
    
    
</div>
    

</div>
        
<div class="MPWSControlField MPWSControlFieldFormButtons">
            <button id="MPWSControlFormButtonsSignInID" type="submit" name="do" value="SignIn" class="MPWSControl MPWSControlButtonSignIn">
            Enter Toolbox
        </button>
    </div>
</form>
    
</div>
</div>
    
</div>

</body>
</html><?php }} ?>