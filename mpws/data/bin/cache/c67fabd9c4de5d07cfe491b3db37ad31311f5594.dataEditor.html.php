<?php /*%%SmartyHeaderCode:105153097350788f668519c0-42081481%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c67fabd9c4de5d07cfe491b3db37ad31311f5594' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1350251343,
      2 => 'file',
    ),
    '001f27e0f6123b935501ab627af0711ee9f6c81f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html',
      1 => 1350066354,
      2 => 'file',
    ),
    '58d5b137345a5af07bb619330436dbfb288a7788' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/databaseField.html',
      1 => 1350246626,
      2 => 'file',
    ),
    '569c0239c22ec9c0530c6339abe971e96503f09c' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlTextBox.html',
      1 => 1350227472,
      2 => 'file',
    ),
    '03c192a2b7a2808a9e905faf14ec3009c8742fe1' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350066354,
      2 => 'file',
    ),
    '6ca457ada6961b80d2daa3bdaa51d6b7748bb949' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlCheckBox.html',
      1 => 1350227394,
      2 => 'file',
    ),
    '4dfe11729f03ab36fc06597fa2c6e7e34650504f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlTextArea.html',
      1 => 1350227467,
      2 => 'file',
    ),
    '036cc594ee99b6926daf04f649ffa4d0d238b8e9' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlDropDown.html',
      1 => 1350227504,
      2 => 'file',
    ),
    'c57828bfbc5d080e08a984bc662a85a828b22044' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350250828,
      2 => 'file',
    ),
    '26c70ee4d97bcc8ae6e6d1f89d9932f6acdb265b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1349812898,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105153097350788f668519c0-42081481',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507b337ad77782_39673535',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507b337ad77782_39673535')) {function content_507b337ad77782_39673535($_smarty_tpl) {?>
<div id="MPWSWidgetSystemUsersID" class="MPWSWidget MPWSWidgetSystemUsers">
    
    
    
            




<div class="MPWSComponent MPWSComponentObjectSummary">
    <span class="MPWSText MPWSTextTitle">User Editor</span>
    <span class="MPWSText MPWSTextDetails"> - </span>
        <span class="MPWSText MPWSTextCustom">Preview Data</span>
    </div>
        
    
        
    
    <form action="" name="data_edit_systemusers" method="POST" class="MPWSForm MPWSFormEditor MPWSFormEditorPagePreview">

    
    
            
        
        
                    
        
<div class="MPWSComponent MPWSComponentDataBaseField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldName">
    <label for="MPWSControlTextBoxNameID">
        <span class="MPWSText">User Name</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxName MPWSControlRenderModeHidden">
    
    
    
    <span class="MPWSControlReadOnlyValue">fdgfdgfdg</span>
    <input type="hidden" name="mpws_field_name" value="fdgfdgfdg"/>
         
    
</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentDataBaseField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldPassword">
    <label for="MPWSControlTextBoxPasswordID">
        <span class="MPWSText">User Password</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxPassword MPWSControlRenderModeHidden">
    
    
    
    <span class="MPWSControlReadOnlyValue">gfgdfgA2s</span>
    <input type="hidden" name="mpws_field_password" value="gfgdfgA2s"/>
         
    
</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentDataBaseField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldActive">
    <label for="MPWSControlCheckBoxActiveID">
        <span class="MPWSText">Check if user is enabled</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxActive MPWSControlRenderModeHidden">

    
        
        <span class="MPWSControlReadOnlyValue">checked</span>
        <input type="hidden" name="mpws_field_active" value="checked">

    

</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentDataBaseField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldPermisions">
    <label for="MPWSControlTextAreaPermisionsID">
        <span class="MPWSText">Access Level</span>
    </label>
</div>
                    
    
<div class="MPWSControlField MPWSControlFieldTextArea MPWSControlFieldTextAreaPermisions MPWSControlRenderModeHidden">
    
    
    <span class="MPWSControlReadOnlyValue">fgfdghgf hghh ghfgh</span>
    <input type="hidden" name="mpws_field_permisions" value="fgfdghgf hghh ghfgh"/>
         
</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentDataBaseField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldRole">
    <label for="MPWSControlDropDownRoleID">
        <span class="MPWSText">User Role</span>
    </label>
</div>
                    
        
<div class="MPWSControlField MPWSControlFieldDropDown MPWSControlFieldDropDownRole MPWSControlRenderModeHidden">
    
    
    
    <span class="MPWSControlReadOnlyValue">Administrator</span>
    <input type="hidden" name="mpws_field_role" checked="SUPERADMIN">


</div>
    

</div>
                
        
    
    
<div class="MPWSControlField MPWSControlFieldFormButtons">
            <button id="MPWSControlFormButtonsSaveID" type="submit" name="do" value="Save" class="MPWSControl MPWSControlButtonSave">
            Save
        </button>
            <button id="MPWSControlFormButtonsEditID" type="submit" name="do" value="Edit" class="MPWSControl MPWSControlButtonEdit">
            Edit
        </button>
            <button id="MPWSControlFormButtonsCancelID" type="submit" name="do" value="Cancel" class="MPWSControl MPWSControlButtonCancel">
            Cancel
        </button>
    </div>
    
    </form>
    
    
    
    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
        
<a href="/page/tools.html?plugin=toolbox&display=users&pg=2" target="_self" class="MPWSLink" title="Back To List">Back To List</a>
    </div>

</div><?php }} ?>