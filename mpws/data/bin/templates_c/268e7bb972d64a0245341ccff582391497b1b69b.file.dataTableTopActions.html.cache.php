<?php /* Smarty version Smarty-3.1.11, created on 2012-10-12 15:50:23
         compiled from "/var/www/mpws/web/default/v1.0/template/component/dataTableTopActions.html" */ ?>
<?php /*%%SmartyHeaderCode:192105536950769505422c85-67918769%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '268e7bb972d64a0245341ccff582391497b1b69b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataTableTopActions.html',
      1 => 1350046170,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192105536950769505422c85-67918769',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50769505452181_22349126',
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
<?php if ($_valid && !is_callable('content_50769505452181_22349126')) {function content_50769505452181_22349126($_smarty_tpl) {?>


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