<?php /* Smarty version Smarty-3.1.11, created on 2012-10-18 23:11:30
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/component/breadcrumb.html" */ ?>
<?php /*%%SmartyHeaderCode:54440418750805fadea6c16-75253972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5aca444bcbbdb34f3c38126e88ce3ee9d25c36b6' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/component/breadcrumb.html',
      1 => 1350590905,
      2 => 'file',
    ),
    'ff723190029025c7eca9b1a09f9c94bdce3626f6' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/breadcrumb.html',
      1 => 1350579434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54440418750805fadea6c16-75253972',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50805fadec7773_13027400',
  'variables' => 
  array (
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50805fadec7773_13027400')) {function content_50805fadec7773_13027400($_smarty_tpl) {?><div class="MPWSComponent MPWSComponentBreadcrumb">
    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'])){?>
        <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'];?>

    <?php }?>

    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])){?>
        \ <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'];?>

    <?php }?>

    
        \ <?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'];?>

    
</div><?php }} ?>