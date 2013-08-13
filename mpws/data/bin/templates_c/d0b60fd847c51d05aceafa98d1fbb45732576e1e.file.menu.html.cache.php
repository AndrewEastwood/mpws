<?php /* Smarty version Smarty-3.1.11, created on 2013-08-14 02:17:50
         compiled from "/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html" */ ?>
<?php /*%%SmartyHeaderCode:149036013520634148e01a4-53112138%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0b60fd847c51d05aceafa98d1fbb45732576e1e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html',
      1 => 1376435866,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '149036013520634148e01a4-53112138',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206341499e119_01872461',
  'variables' => 
  array (
    '_OBJ' => 0,
    'CURRENT' => 0,
    'OBJECT' => 0,
    'DOs' => 0,
    '_showDescription' => 0,
    '_sub' => 0,
    '_subIndex' => 0,
    '_items' => 0,
    'keyvar' => 0,
    'DISPLAY_OBJECT' => 0,
    'itemvar' => 0,
    '_linkText' => 0,
    'showDescription' => 0,
    'webObj' => 0,
    'submenu' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206341499e119_01872461')) {function content_5206341499e119_01872461($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><?php $_smarty_tpl->tpl_vars["DOs"] = new Smarty_variable(array(), null, 0);?>
<?php if ((isset($_smarty_tpl->tpl_vars['_OBJ']->value))){?>
    <?php $_smarty_tpl->tpl_vars["DOs"] = new Smarty_variable(array($_smarty_tpl->tpl_vars['_OBJ']->value), null, 0);?>
<?php }?>
<?php $_smarty_tpl->createLocalArrayVariable('DOs', null, 0);
$_smarty_tpl->tpl_vars['DOs']->value[] = $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT'];?>
<?php $_smarty_tpl->createLocalArrayVariable('DOs', null, 0);
$_smarty_tpl->tpl_vars['DOs']->value[] = $_smarty_tpl->tpl_vars['OBJECT']->value['SITE'];?>
<?php $_smarty_tpl->tpl_vars["DISPLAY_OBJECT"] = new Smarty_variable(glGetFirstNonEmptyValue($_smarty_tpl->tpl_vars['DOs']->value), null, 0);?>

<?php $_smarty_tpl->tpl_vars['showDescription'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_showDescription']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars['_subIndex'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_sub']->value)===null||$tmp==='' ? 0 : $tmp), null, 0);?>


<?php if (empty($_smarty_tpl->tpl_vars['_subIndex']->value)){?>
<div class="MPWSComponent MPWSComponenMenu">
<?php }?>

    <?php if (isset($_smarty_tpl->tpl_vars['_items']->value)){?>
    
    <?php if (empty($_smarty_tpl->tpl_vars['_subIndex']->value)){?>
        <ul class="MPWSList MPWSListMenu">
    <?php }else{ ?>
        <ul class="MPWSList MPWSListMenuSub MPWSListMenuSub<?php echo $_smarty_tpl->tpl_vars['_subIndex']->value;?>
">
    <?php }?>
        
    <?php  $_smarty_tpl->tpl_vars['itemvar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itemvar']->_loop = false;
 $_smarty_tpl->tpl_vars['keyvar'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itemvar']->key => $_smarty_tpl->tpl_vars['itemvar']->value){
$_smarty_tpl->tpl_vars['itemvar']->_loop = true;
 $_smarty_tpl->tpl_vars['keyvar']->value = $_smarty_tpl->tpl_vars['itemvar']->key;
?>
        <li class="MPWSListItem">
            <?php $_smarty_tpl->tpl_vars['_linkText'] = new Smarty_variable($_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{"objectProperty_display_menuText".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['keyvar']->value,0,1))}, null, 0);?>
            <a href="<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsAutoLinkHref, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('link'=>$_smarty_tpl->tpl_vars['itemvar']->value['link'],'display'=>$_smarty_tpl->tpl_vars['keyvar']->value), 0);?>
" target="<?php echo $_smarty_tpl->tpl_vars['itemvar']->value['target'];?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>
">
                <span class="MPWSText MPWSTextTitle"><?php echo $_smarty_tpl->tpl_vars['_linkText']->value;?>
</span>
                <?php if ($_smarty_tpl->tpl_vars['showDescription']->value){?>
                <span class="MPWSText MPWSTextDescription"><?php echo $_smarty_tpl->tpl_vars['DISPLAY_OBJECT']->value->{"objectProperty_display_menuTextDescription".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['keyvar']->value,0,1))};?>
</span>
                <?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['itemvar']->value['contains'])){?>
                    <span class="MPWSIconArrow"></span>
                <?php }?>
            </a>
            <?php if (isset($_smarty_tpl->tpl_vars['itemvar']->value['contains'])&&($_smarty_tpl->tpl_vars['itemvar']->value['contains']=='__PLUGINS__')){?>
            <ul class="MPWSList MPWSListMenuSub MPWSListMenuSub<?php echo $_smarty_tpl->tpl_vars['_subIndex']->value+1;?>
">
                <?php  $_smarty_tpl->tpl_vars['webObj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['webObj']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['OBJECT']->value['WOB']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['webObj']->key => $_smarty_tpl->tpl_vars['webObj']->value){
$_smarty_tpl->tpl_vars['webObj']->_loop = true;
?>
                    <li class="MPWSListItem">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_object_menuExecObjectPath;?>
?plugin=<?php echo $_smarty_tpl->tpl_vars['webObj']->value->getObjectName();?>
" class="MPWSLink" title="<?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
">
                            <span class="MPWSText"><?php echo $_smarty_tpl->tpl_vars['webObj']->value->objectProperty_widget_systemPluginListTitle;?>
</span>
                        </a>
                        <?php $_smarty_tpl->tpl_vars['submenu'] = new Smarty_variable($_smarty_tpl->tpl_vars['webObj']->value->objectConfiguration_display_menuPlugin, null, 0);?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['submenu']->value)){?>
                            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_menu, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_items'=>$_smarty_tpl->tpl_vars['submenu']->value,'_OBJ'=>$_smarty_tpl->tpl_vars['webObj']->value,'_sub'=>$_smarty_tpl->tpl_vars['_subIndex']->value+2), 0);?>

                        <?php }?>
                    </li>
                <?php } ?>
            </ul>
            <?php }?>
        </li>
    <?php } ?>
    </ul>
    <?php }?>
    
<?php if (empty($_smarty_tpl->tpl_vars['_subIndex']->value)){?>
</div>
<?php }?><?php }} ?>