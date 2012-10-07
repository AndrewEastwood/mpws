<?php /* Smarty version Smarty-3.1.11, created on 2012-10-07 14:59:30
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPublicPageStyle1.html" */ ?>
<?php /*%%SmartyHeaderCode:68254641250716ea288f3b4-52689664%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fd493ce1d9a8de3661d7b6fa94771c833d559aee' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPublicPageStyle1.html',
      1 => 1349288366,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '68254641250716ea288f3b4-52689664',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INFO' => 0,
    'CURRENT' => 0,
    '_header' => 0,
    '_content' => 0,
    '_footer' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50716ea28c9127_11240094',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50716ea28c9127_11240094')) {function content_50716ea28c9127_11240094($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/media/sda3/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?>
<div class="MPWSPage MPWSPageDisplay<?php echo smarty_modifier_capitalize(libraryRequest::getDisplay(),0,1);?>
 MPWSPageStandartPublicPageStyle1 MPWSPageStandartPublic<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['DISPLAY'],0,1);?>
" id="MPWSPageStandartPublic<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['PAGE'],0,1);?>
ID">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageHeader, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_header']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageContent, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_content']->value), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_pageFooter, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_data'=>$_smarty_tpl->tpl_vars['_footer']->value), 0);?>

</div><?php }} ?>