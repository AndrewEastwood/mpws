<?php /* Smarty version Smarty-3.1.11, created on 2012-10-11 12:14:40
         compiled from "/var/www/mpws/web/default/v1.0/template/page/login.html" */ ?>
<?php /*%%SmartyHeaderCode:26513818850768e00380a67-87962096%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f63ea4f5726960dab8f6678067d6aa26dcc52b19' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/login.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '26513818850768e00380a67-87962096',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50768e0038f052_85361937',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50768e0038f052_85361937')) {function content_50768e0038f052_85361937($_smarty_tpl) {?><div class="MPWSPage MPWSPageLogin">

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