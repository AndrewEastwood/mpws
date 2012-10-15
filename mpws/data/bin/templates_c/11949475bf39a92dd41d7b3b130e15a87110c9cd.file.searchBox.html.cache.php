<?php /* Smarty version Smarty-3.1.11, created on 2012-10-15 08:57:58
         compiled from "/var/www/mpws/web/default/v1.0/template/component/searchBox.html" */ ?>
<?php /*%%SmartyHeaderCode:17149719507ba5e6a38553-38073681%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '11949475bf39a92dd41d7b3b130e15a87110c9cd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/searchBox.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17149719507ba5e6a38553-38073681',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT' => 0,
    '_confing' => 0,
    '_data' => 0,
    'field' => 0,
    'fieldKey' => 0,
    '_fieldValue' => 0,
    'sbKey' => 0,
    'sbVal' => 0,
    '_filterString' => 0,
    'srchl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ba5e6af4eb6_49306040',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ba5e6af4eb6_49306040')) {function content_507ba5e6af4eb6_49306040($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/mydata/GitHub/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_component_simpleHeader, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_title'=>$_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectProperty_component_searchBoxHeader), 0);?>

    <div class="MPWSComponentBody">
        <form action="<?php echo $_smarty_tpl->tpl_vars['_confing']->value['searchbox']['formAction'];?>
" class="MPWSForm MPWSFormSearchBox" method="POST">
            <div class="MPWSFormFields">
            <?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldKey']->value = $_smarty_tpl->tpl_vars['field']->key;
?>
                <?php $_smarty_tpl->tpl_vars["_fieldValue"] = new Smarty_variable(libraryRequest::getPostValue($_smarty_tpl->tpl_vars['field']->value), null, 0);?>
                <div class="MPWSFormField MPWSFormField<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['fieldKey']->value,0,1);?>
">
                    <label class="MPWSFieldLabel"><?php echo $_smarty_tpl->tpl_vars['fieldKey']->value;?>
</label>
                    <input type="text" class="MPWSTextBox" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['_fieldValue']->value;?>
" placeholder="... part of title"/>
                </div>
                <div class="MPWSSeparator"></div>
            <?php } ?>
            </div>
            <div class="MPWSBlock MPWSBlockFormControls">
                <input type="submit" name="do" value="Search"/>
            <?php if ($_smarty_tpl->tpl_vars['_data']->value['ACTIVE']){?>
                <input type="submit" name="do" value="Discard"/>
            <?php }?>
            </div>
        </form>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['_data']->value['ACTIVE']){?>
    <div class="MPWSComponentSummary">
        <div class="MPWSComponentSummaryRow">
            <label>Your search request is:</label>
            <span class="MPWSValue">
            <?php  $_smarty_tpl->tpl_vars['sbVal'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sbVal']->_loop = false;
 $_smarty_tpl->tpl_vars['sbKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_data']->value['WORDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sbVal']->key => $_smarty_tpl->tpl_vars['sbVal']->value){
$_smarty_tpl->tpl_vars['sbVal']->_loop = true;
 $_smarty_tpl->tpl_vars['sbKey']->value = $_smarty_tpl->tpl_vars['sbVal']->key;
?>
                <?php $_smarty_tpl->createLocalArrayVariable('_filterString', null, 0);
$_smarty_tpl->tpl_vars['_filterString']->value[] = "field ".((string)$_smarty_tpl->tpl_vars['sbKey']->value)." contains value ".((string)$_smarty_tpl->tpl_vars['sbVal']->value);?>
            <?php } ?>
            <?php $_smarty_tpl->tpl_vars['srchl'] = new Smarty_variable(implode(", and the ",$_smarty_tpl->tpl_vars['_filterString']->value), null, 0);?>
            The <?php echo $_smarty_tpl->tpl_vars['srchl']->value;?>

            </span>
        </div>
    </div>
    <?php }?>
</div><?php }} ?>