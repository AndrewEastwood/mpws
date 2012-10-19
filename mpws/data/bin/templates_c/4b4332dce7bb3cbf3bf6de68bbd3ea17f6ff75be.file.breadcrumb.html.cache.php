<?php /* Smarty version Smarty-3.1.11, created on 2012-10-19 16:34:09
         compiled from "/var/www/mpws/web/customer/toolbox/template/component/breadcrumb.html" */ ?>
<?php /*%%SmartyHeaderCode:1596175720508156d1eab475-02699441%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b4332dce7bb3cbf3bf6de68bbd3ea17f6ff75be' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/component/breadcrumb.html',
      1 => 1350627484,
      2 => 'file',
    ),
    '240c0ec7052d952b311c2c1e10414aadecb967f7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/breadcrumb.html',
      1 => 1350563538,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1596175720508156d1eab475-02699441',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508156d2040bb5_10599186',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508156d2040bb5_10599186')) {function content_508156d2040bb5_10599186($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentBreadcrumb">
    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'])){?>
        <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'];?>

    <?php }?>

    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])){?>
        \ <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'];?>

    <?php }?>

    
        \ <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'];?>

    
</div><?php }} ?>