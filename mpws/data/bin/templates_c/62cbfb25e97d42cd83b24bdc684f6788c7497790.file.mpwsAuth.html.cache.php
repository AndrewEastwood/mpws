<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 19:25:52
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsAuth.html" */ ?>
<?php /*%%SmartyHeaderCode:130302746050817f1004b753-66731490%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '62cbfb25e97d42cd83b24bdc684f6788c7497790' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsAuth.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '130302746050817f1004b753-66731490',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_action' => 0,
    'MODEL' => 0,
    '_formAction' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50817f100ba755_61555507',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50817f100ba755_61555507')) {function content_50817f100ba755_61555507($_smarty_tpl) {?><div class="MPWSControl MPWSControlMpws MPWSControlMpwsAuth">
<?php $_smarty_tpl->tpl_vars['_formAction'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_action']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['MODEL']->value['CUSTOM']['LOGIN_URL'] : $tmp), null, 0);?>
<form action="?<?php echo $_smarty_tpl->tpl_vars['_formAction']->value;?>
" method="POST" class="MPWSForm">
    <div class="MPWSFormHeader">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_header, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_key'=>'mpwsAuthBox'), 0);?>

    </div>
    <div class="MPWSFormBody">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'textbox','_name'=>'Login','_ownerName'=>"mpwsAuthBox"), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_trigger_control, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_type'=>'password','_name'=>'Password','_value'=>'','_ownerName'=>"mpwsAuthBox"), 0);?>

    </div>
    <div class="MPWSFormFooter">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('SignIn'),'_ownerName'=>'mpwsAuthBox'), 0);?>

    </div>
</form>
</div><?php }} ?>