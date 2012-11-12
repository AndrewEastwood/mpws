<?php /* Smarty version Smarty-3.1.11, created on 2012-11-09 10:50:24
         compiled from "/var/www/mpws/web/default/v1.0/template/control/mpwsRTEWH.html" */ ?>
<?php /*%%SmartyHeaderCode:11989199675090d43a5c6314-94660691%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86450563fefaf58062888d7801264461ac6d720a' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsRTEWH.html',
      1 => 1352450948,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11989199675090d43a5c6314-94660691',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5090d43a62bf64_56287376',
  'variables' => 
  array (
    '_useForm' => 0,
    '_sources' => 0,
    '_name' => 0,
    '_jslib' => 0,
    'CURRENT' => 0,
    '_type' => 0,
    'uuid' => 0,
    'controlID' => 0,
    '_controlNameData' => 0,
    '_value' => 0,
    '_controlSources' => 0,
    '_controlNameSource' => 0,
    '_useDivWrapper' => 0,
    'jsControlName' => 0,
    '_cols' => 0,
    '_rows' => 0,
    '_renderMode' => 0,
    '_controlOwner' => 0,
    '_controlCssName' => 0,
    '_controlUseForm' => 0,
    'formID' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5090d43a62bf64_56287376')) {function content_5090d43a62bf64_56287376($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_controlOwner'] = new Smarty_variable('RTEWH', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlCssName'] = new Smarty_variable('RichTextEditWithHightlight', null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlUseForm'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_useForm']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlSources'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_sources']->value)===null||$tmp==='' ? array() : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlNameData'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8'))."_data", null, 0);?>
<?php $_smarty_tpl->tpl_vars['_controlNameSource'] = new Smarty_variable("mpws_field_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8'))."_source", null, 0);?>

<?php if (!empty($_smarty_tpl->tpl_vars['_jslib']->value)){?>
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_simple_script, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_link'=>$_smarty_tpl->tpl_vars['_jslib']->value), 0);?>

<?php }?>


<?php if ($_smarty_tpl->tpl_vars['_type']->value=='ACE'){?>
    
    <?php $_smarty_tpl->_capture_stack[0][] = array("controlBody", null, null); ob_start(); ?>
        <?php $_smarty_tpl->tpl_vars['uuid'] = new Smarty_variable(md5(time()), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['controlID'] = new Smarty_variable("MPWSControlUUID_".((string)$_smarty_tpl->tpl_vars['uuid']->value)."_ID", null, 0);?>
        <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['controlID']->value;?>
" name="<?php echo $_smarty_tpl->tpl_vars['_controlNameData']->value;?>
" value="<?php echo htmlentities($_smarty_tpl->tpl_vars['_value']->value);?>
"/>
        <?php if (!empty($_smarty_tpl->tpl_vars['_controlSources']->value)){?>
            <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_controlNameSource']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_controlSources']->value;?>
"/>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['_useDivWrapper']->value){?>
            <div id="MPWSWrapperRTEWH_<?php echo mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8');?>
_ID" class="MPWSWrapper MPWSWrapperRTEWH"><?php echo $_smarty_tpl->tpl_vars['_value']->value;?>
</div>
            
            <style type="text/css" media="screen">
                .MPWSBockReportScriptEditArea .MPWSWrapperRTEWH { 
                    position: relative;
                    width: 100%;
                    height: 400px;
                    font-size: 16px;
                }
            </style>
            <?php $_smarty_tpl->tpl_vars['jsControlName'] = new Smarty_variable("\"MPWSWrapperRTEWH_".((string)mb_strtolower($_smarty_tpl->tpl_vars['_name']->value, 'UTF-8'))."_ID\"", null, 0);?>
            <script>
                /* standart setup */
                var editor = ace.edit(<?php echo $_smarty_tpl->tpl_vars['jsControlName']->value;?>
);
                editor.setTheme("ace/theme/monokai");
                editor.getSession().setMode("ace/mode/javascript");
                editor.getSession().setUseWrapMode(true);
                /* custom handler */
                editor.getSession().on('change', function(e) {
                    $('#<?php echo $_smarty_tpl->tpl_vars['controlID']->value;?>
').val(editor.getSession().getValue());
                });
            </script>
        <?php }else{ ?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_htmlTextArea, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_name'=>$_smarty_tpl->tpl_vars['_name']->value,'_cols'=>(($tmp = @$_smarty_tpl->tpl_vars['_cols']->value)===null||$tmp==='' ? null : $tmp),'_rows'=>(($tmp = @$_smarty_tpl->tpl_vars['_rows']->value)===null||$tmp==='' ? null : $tmp),'_renderMode'=>(($tmp = @$_smarty_tpl->tpl_vars['_renderMode']->value)===null||$tmp==='' ? 'normal' : $tmp),'_value'=>$_smarty_tpl->tpl_vars['_value']->value), 0);?>

        <?php }?>
    <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
    
    <?php $_smarty_tpl->_capture_stack[0][] = array("controlButtons", null, null); ob_start(); ?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_control_mpwsFormButtons, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_buttons'=>array('Save','Cancel'),'_controlOwner'=>$_smarty_tpl->tpl_vars['_controlOwner']->value,'_resourceOwner'=>'control'), 0);?>

    <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php }?>



<div class="MPWSControlField MPWSControlField<?php echo $_smarty_tpl->tpl_vars['_controlCssName']->value;?>
">
<?php if ($_smarty_tpl->tpl_vars['_controlUseForm']->value){?>
    <?php $_smarty_tpl->tpl_vars['formID'] = new Smarty_variable("MPWSFromUUID_".((string)$_smarty_tpl->tpl_vars['uuid']->value)."_ID", null, 0);?>
    <form action="" method="POST" id="<?php echo $_smarty_tpl->tpl_vars['formID']->value;?>
" class="MPWSForm">
        <div class="MPWSFormHeader"><?php echo Smarty::$_smarty_vars['capture']['controlButtons'];?>
</div>
        <div class="MPWSFormBody"><?php echo Smarty::$_smarty_vars['capture']['controlBody'];?>
</div>
        <div class="MPWSFormFooter"><?php echo Smarty::$_smarty_vars['capture']['controlButtons'];?>
</div>
    </form>
<?php }else{ ?>
    <?php echo Smarty::$_smarty_vars['capture']['controlButtons'];?>

    <?php echo Smarty::$_smarty_vars['capture']['controlBody'];?>

    <?php echo Smarty::$_smarty_vars['capture']['controlButtons'];?>

<?php }?>
</div><?php }} ?>