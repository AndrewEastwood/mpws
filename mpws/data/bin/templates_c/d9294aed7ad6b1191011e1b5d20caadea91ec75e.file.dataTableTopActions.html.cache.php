<?php /* Smarty version Smarty-3.1.11, created on 2012-10-10 00:51:13
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTableTopActions.html" */ ?>
<?php /*%%SmartyHeaderCode:32371600450747bb78c7ce2-48065552%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9294aed7ad6b1191011e1b5d20caadea91ec75e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTableTopActions.html',
      1 => 1349819471,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32371600450747bb78c7ce2-48065552',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50747bb79b04e7_59710025',
  'variables' => 
  array (
    '_data' => 0,
    '_confing' => 0,
    'CURRENT' => 0,
    '_ownerName' => 0,
    '_actionName' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50747bb79b04e7_59710025')) {function content_50747bb79b04e7_59710025($_smarty_tpl) {?>


<div id="MPWSComponentDataTableTopActionsID" class="MPWSComponent MPWSComponentDataTableTopActions">


<?php if (count($_smarty_tpl->tpl_vars['_data']->value)==0){?>

    

<?php }elseif(count($_smarty_tpl->tpl_vars['_data']->value)>0){?>

    
    <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions'])){?>
        <?php  $_smarty_tpl->tpl_vars['_actionName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_actionName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_actionName']->key => $_smarty_tpl->tpl_vars['_actionName']->value){
$_smarty_tpl->tpl_vars['_actionName']->_loop = true;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_actionLink, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_ownerName'=>$_smarty_tpl->tpl_vars['_ownerName']->value,'_action'=>$_smarty_tpl->tpl_vars['_actionName']->value), 0);?>

        <?php } ?>
    <?php }?>

<?php }?>
</div><?php }} ?>