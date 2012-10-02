<?php /* Smarty version Smarty-3.1.11, created on 2012-10-02 21:30:38
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemUserInfo.html" */ ?>
<?php /*%%SmartyHeaderCode:1920901160506b32cea43e79-63655215%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '69efce9731aeeb54451ddefc0c66f864c1ae09b4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1348684566,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1920901160506b32cea43e79-63655215',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SITE' => 0,
    'INFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506b32cea6c847_40420266',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506b32cea6c847_40420266')) {function content_506b32cea6c847_40420266($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.date_format.php';
?><div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectProperty_user_systemWelcomeMessage;?>
</span>
            <span class="MPWSValue"><?php echo $_smarty_tpl->tpl_vars['INFO']->value['USER']['NAME'];?>
</span>
        </div><div class="MPWSLabelValueRow">
            <span class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectProperty_user_systemUserLastAccess;?>
</span>
            <span class="MPWSValue"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['INFO']->value['USER']['SINCE'],$_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_mdbc_datetimeFormat);?>
</span>
        </div>
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel"><?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectProperty_site_homePage;?>
</span>
            <span class="MPWSValue"><a href="<?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectConfiguration_customer_homepage;?>
" target="blank" class="MPWSLink"><?php echo @MPWS_CUSTOMER;?>
</a></span>
        </div>
        <button type="submit" name="do" value="logout" class="MPWSButton"><?php echo $_smarty_tpl->tpl_vars['SITE']->value->objectProperty_user_formSignoutButtonText;?>
</button>
    </form>
</div><?php }} ?>