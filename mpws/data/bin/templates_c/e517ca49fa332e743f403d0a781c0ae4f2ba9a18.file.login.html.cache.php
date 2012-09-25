<?php /* Smarty version Smarty-3.1.11, created on 2012-09-25 22:49:56
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/login.html" */ ?>
<?php /*%%SmartyHeaderCode:17474339565060bc6e635976-38862541%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e517ca49fa332e743f403d0a781c0ae4f2ba9a18' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/login.html',
      1 => 1348601317,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17474339565060bc6e635976-38862541',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5060bc6e65ce54_78274000',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5060bc6e65ce54_78274000')) {function content_5060bc6e65ce54_78274000($_smarty_tpl) {?><div class="MPWSPage MPWSPageLogin">
    
    
    
    <<?php ?>?=(empty($_SERVER['QUERY_STRING'])?'display=home':libraryRequest::getNewUrl());?<?php ?>>
    
<form action="?" method="POST">
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
    
</div><?php }} ?>