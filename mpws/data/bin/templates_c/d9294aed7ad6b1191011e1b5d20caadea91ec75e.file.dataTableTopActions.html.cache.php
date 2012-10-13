<?php /* Smarty version Smarty-3.1.11, created on 2012-10-13 00:45:04
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTableTopActions.html" */ ?>
<?php /*%%SmartyHeaderCode:120275804950788f60aa4c84-48952417%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9294aed7ad6b1191011e1b5d20caadea91ec75e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTableTopActions.html',
      1 => 1350068577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '120275804950788f60aa4c84-48952417',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_data' => 0,
    '_confing' => 0,
    'CURRENT' => 0,
    '_ownerName' => 0,
    '_actionName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50788f60acefc8_85164115',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50788f60acefc8_85164115')) {function content_50788f60acefc8_85164115($_smarty_tpl) {?>


<div id="MPWSComponentDataTableTopActionsID" class="MPWSComponent MPWSComponentDataTableTopActions">


<?php if (count($_smarty_tpl->tpl_vars['_data']->value)==0){?>

    

<?php }elseif(count($_smarty_tpl->tpl_vars['_data']->value)>0){?>

    
    <?php if (isset($_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions'])){?>
        <?php  $_smarty_tpl->tpl_vars['_actionName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_actionName']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_confing']->value['datatable']['tableTopActions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_actionName']->key => $_smarty_tpl->tpl_vars['_actionName']->value){
$_smarty_tpl->tpl_vars['_actionName']->_loop = true;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_actionLink, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_ownerName'=>$_smarty_tpl->tpl_vars['_ownerName']->value,'_action'=>$_smarty_tpl->tpl_vars['_actionName']->value,'_realm'=>"TopAction"), 0);?>

        <?php } ?>
    <?php }?>

<?php }?>
</div><?php }} ?>