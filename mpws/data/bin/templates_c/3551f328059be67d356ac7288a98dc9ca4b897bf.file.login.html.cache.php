<?php /* Smarty version Smarty-3.1.11, created on 2012-09-24 23:02:08
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/login.html" */ ?>
<?php /*%%SmartyHeaderCode:704778131505f851c2573a1-54953079%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3551f328059be67d356ac7288a98dc9ca4b897bf' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/login.html',
      1 => 1348516927,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '704778131505f851c2573a1-54953079',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505f851c259e63_08929352',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505f851c259e63_08929352')) {function content_505f851c259e63_08929352($_smarty_tpl) {?><div class="MPWSContentDisplay MPWSContentDisplayLogin">


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