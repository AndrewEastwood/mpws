<?php /* Smarty version Smarty-3.1.11, created on 2012-10-31 09:33:14
         compiled from "/var/www/mpws/web/default/v1.0/template/simple/script.html" */ ?>
<?php /*%%SmartyHeaderCode:8255360925090d43a633343-93477442%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd50ca854d9b53b914b469e5fb7a08f31d05a4ff3' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/script.html',
      1 => 1351665939,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8255360925090d43a633343-93477442',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_link' => 0,
    '_source' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5090d43a6468f3_84379632',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5090d43a6468f3_84379632')) {function content_5090d43a6468f3_84379632($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['_link']->value)){?>
<script src="<?php echo $_smarty_tpl->tpl_vars['_link']->value;?>
" type="text/javascript"></script>
<?php }elseif(!empty($_smarty_tpl->tpl_vars['_source']->value)){?>
<script type="text/javascript"><?php echo $_smarty_tpl->tpl_vars['_source']->value;?>
</script>
<?php }?><?php }} ?>