<?php /* Smarty version Smarty-3.1.11, created on 2012-10-13 01:15:35
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/login.html" */ ?>
<?php /*%%SmartyHeaderCode:1059413055507896874807a9-13047461%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e517ca49fa332e743f403d0a781c0ae4f2ba9a18' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/login.html',
      1 => 1349287442,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1059413055507896874807a9-13047461',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507896874c7b05_23903338',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507896874c7b05_23903338')) {function content_507896874c7b05_23903338($_smarty_tpl) {?><div class="MPWSPage MPWSPageLogin">

<form action="?<?php echo $_smarty_tpl->tpl_vars['MODEL']->value['CUSTOM']['LOGIN_URL'];?>
" method="POST">
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