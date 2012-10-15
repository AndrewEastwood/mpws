<?php /*%%SmartyHeaderCode:185444062507bb752d21ec9-66454334%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be7e5e2b15fcfe9553056b2b52a6c64faaf67f44' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1350323107,
      2 => 'file',
    ),
    'b2aaf23207bc3ef5251be5270ea54d71f48d9a4e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/objectSummary.html',
      1 => 1350057923,
      2 => 'file',
    ),
    '3349bdbf34090f0c005554152d092228d9ae2049' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/controlFieldSwitcher.html',
      1 => 1350321182,
      2 => 'file',
    ),
    '895b30db561d11a1f49691acbd28353b8e852c1e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlTextBox.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'f91858edc3c80e6c6d6bf864d185f1e815a16972' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350031670,
      2 => 'file',
    ),
    '854b0970c18a373da7a97c4141a74b667cea772f' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlCheckBox.html',
      1 => 1350296452,
      2 => 'file',
    ),
    'b764f0eae8ec73c911b59b3da8bd838af3685ae1' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlTextArea.html',
      1 => 1350280496,
      2 => 'file',
    ),
    '6a5eea36a40da3d1a0c85bc196311fe789bc0104' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlDropDown.html',
      1 => 1350295738,
      2 => 'file',
    ),
    '903d91440aad38a59f6d2af5f4a037f8c1d06e72' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'fa3ac31b0451d94d3da0caded88fd2ba177c2310' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185444062507bb752d21ec9-66454334',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507c4c84360d15_12589236',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507c4c84360d15_12589236')) {function content_507c4c84360d15_12589236($_smarty_tpl) {?>
<div id="MPWSWidgetDataEditorSystemUsersID" class="MPWSWidget MPWSWidgetDataEditor MPWSWidgetDataEditorSystemUsers">
    
    
    
            




<div class="MPWSComponent MPWSComponentObjectSummary">
    <span class="MPWSText MPWSTextTitle">User Editor</span>
    <span class="MPWSText MPWSTextDetails"> - </span>
        <span class="MPWSText MPWSTextCustom">Preview Data</span>
    </div>
        
    
        
    
    <form action="" name="data_edit_systemusers" method="POST" class="MPWSForm MPWSFormEditor MPWSFormEditorPagePreview">

    
    
            
        
        
                    
        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldName">
    <label for="MPWSControlTextBoxNameID">
        <span class="MPWSText">User Name</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxName MPWSControlRenderModeHidden">
    
    
    
    <span class="MPWSControlReadOnlyValue">demouser</span>
    <input type="hidden" name="mpws_field_name" value="demouser"/>
         
    
</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldPassword">
    <label for="MPWSControlTextBoxPasswordID">
        <span class="MPWSText">User Password</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxPassword MPWSControlRenderModeHidden">
    
    
    
    <span class="MPWSControlReadOnlyValue">UUUDemo1!</span>
    <input type="hidden" name="mpws_field_password" value="UUUDemo1!"/>
         
    
</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldActive">
    <label for="MPWSControlCheckBoxActiveID">
        <span class="MPWSText">Check if user is enabled</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxActive MPWSControlRenderModeHidden">

    
        
                    <span class="MPWSControlReadOnlyValue">ON</span>
            <input type="hidden" name="mpws_field_active" value="1">
        
    

</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldPermisions">
    <label for="MPWSControlTextAreaPermisionsID">
        <span class="MPWSText">Access Level</span>
    </label>
</div>
                    
    
<div class="MPWSControlField MPWSControlFieldTextArea MPWSControlFieldTextAreaPermisions MPWSControlRenderModeHidden">
    
    
    <span class="MPWSControlReadOnlyValue">*.*</span>
    <input type="hidden" name="mpws_field_permisions" value="*.*"/>
         
</div>
    

</div>
                    
        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeHidden">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldRole">
    <label for="MPWSControlDropDownRoleID">
        <span class="MPWSText">User Role</span>
    </label>
</div>
                    
        
<div class="MPWSControlField MPWSControlFieldDropDown MPWSControlFieldDropDownRole MPWSControlRenderModeHidden">
    
    
    
    <span class="MPWSControlReadOnlyValue">Administrator</span>
    <input type="hidden" name="mpws_field_role" value="SUPERADMIN">


</div>
    

</div>
                
        
    
            
    
<div class="MPWSComponent MPWSComponentField MPWSRenderModeNormal">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldSendEmail">
    <label for="MPWSControlCheckBoxSendEmailID">
        <span class="MPWSText">Notify user by email</span>
    </label>
</div>
                    

        


<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxSendEmail MPWSControlRenderModeNormal">

            
                    <input id="MPWSControlCheckBoxSendEmailID" type="checkbox" name="mpws_field_sendemail" checked class="MPWSControl MPWSControlCheckBox MPWSControlCheckBoxSendEmail">
            

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
        
<a href="/page/tools.html?plugin=toolbox&display=users" target="_self" class="MPWSLink" title="Back To List">Back To List</a>
    </div>

</div><?php }} ?>