<?php /*%%SmartyHeaderCode:105153097350788f668519c0-42081481%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c67fabd9c4de5d07cfe491b3db37ad31311f5594' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1350078058,
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
      1 => 1350078082,
      2 => 'file',
    ),
    '569c0239c22ec9c0530c6339abe971e96503f09c' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlTextBox.html',
      1 => 1350083645,
      2 => 'file',
    ),
    '03c192a2b7a2808a9e905faf14ec3009c8742fe1' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350066354,
      2 => 'file',
    ),
    '4dfe11729f03ab36fc06597fa2c6e7e34650504f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlTextArea.html',
      1 => 1350083625,
      2 => 'file',
    ),
    '6ca457ada6961b80d2daa3bdaa51d6b7748bb949' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlCheckBox.html',
      1 => 1350087283,
      2 => 'file',
    ),
    '036cc594ee99b6926daf04f649ffa4d0d238b8e9' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlDropDown.html',
      1 => 1350086688,
      2 => 'file',
    ),
    '0e0a8b4e18cb941db66afce81ddbbb8a7cf26093' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlDateTime.html',
      1 => 1350084904,
      2 => 'file',
    ),
    'c57828bfbc5d080e08a984bc662a85a828b22044' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350066354,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105153097350788f668519c0-42081481',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5078b286575f68_27881581',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5078b286575f68_27881581')) {function content_5078b286575f68_27881581($_smarty_tpl) {?>
<div id="MPWSWidgetSystemUsersID" class="MPWSWidget MPWSWidgetSystemUsers">
    
                
    
    




<div class="MPWSComponent MPWSComponentObjectSummary">
    <span class="MPWSText MPWSTextTitle">User Editor</span>
    <span class="MPWSText MPWSTextDetails"> - </span>
        <span class="MPWSText MPWSTextCustom">Editing record</span>
    </div>
    
    
    <form action="" name="data_edit_systemusers" method="POST" class="MPWSForm MPWSFormEditor MPWSFormEditorPageEdit">

    
    
        
        
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldID">
    <label for="MPWSControlTextBoxIDID">
        <span class="MPWSText">Record ID</span>
    </label>
</div>
                            

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxID">
    
    
    
    <input id="MPWSControlTextBoxIDID" type="text" name="mpws_field_id" value="" size="25" maxlength="10" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxID">
           
    
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldName">
    <label for="MPWSControlTextBoxNameID">
        <span class="MPWSText">User Name</span>
    </label>
</div>
                            

        


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxName">
    
    
    
    <input id="MPWSControlTextBoxNameID" type="text" name="mpws_field_name" value="" size="25" maxlength="100" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxName">
           
    
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldPassword">
    <label for="MPWSControlTextAreaPasswordID">
        <span class="MPWSText">User Password</span>
    </label>
</div>
                            
    
<div class="MPWSControlField MPWSControlFieldTextArea MPWSControlFieldTextAreaPassword">
    
    
    <textarea id="MPWSControlTextAreaPasswordID" name="mpws_field_password" cols="45" rows="6" class="MPWSControl MPWSControlTextArea MPWSControlTextAreaPassword"></textarea>
           
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldActive">
    <label for="MPWSControlCheckBoxActiveID">
        <span class="MPWSText">Check if user is enabled</span>
    </label>
</div>
                            

    
<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxActive">
    
    
    
    
    <input id="MPWSControlCheckBoxActiveID" type="checkbox" name="mpws_field_active" checked class="MPWSControl MPWSControlCheckBox MPWSControlCheckBoxActive">
           
           
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldIsOnline">
    <label for="MPWSControlCheckBoxIsOnlineID">
        <span class="MPWSText">Current status</span>
    </label>
</div>
                            

    
<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxIsOnline">
    
    
    
    
    <input id="MPWSControlCheckBoxIsOnlineID" type="checkbox" name="mpws_field_isonline" checked class="MPWSControl MPWSControlCheckBox MPWSControlCheckBoxIsOnline">
           
           
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldPermisions">
    <label for="MPWSControlTextAreaPermisionsID">
        <span class="MPWSText">Access Level</span>
    </label>
</div>
                            
    
<div class="MPWSControlField MPWSControlFieldTextArea MPWSControlFieldTextAreaPermisions">
    
    
    <textarea id="MPWSControlTextAreaPermisionsID" name="mpws_field_permisions" cols="45" rows="6" class="MPWSControl MPWSControlTextArea MPWSControlTextAreaPermisions"></textarea>
           
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldRole">
    <label for="MPWSControlDropDownRoleID">
        <span class="MPWSText">User Role</span>
    </label>
</div>
                            
        
<div class="MPWSControlField MPWSControlFieldDropDown MPWSControlFieldDropDownRole">
    
    
    
    
    <select id="MPWSControlDropDownRoleID" type="checkbox" name="mpws_field_role" class="MPWSControl MPWSControlDropDown MPWSControlDropDownRole">
                                <option value="SUPERADMIN">
                            Administrator
            </option>
                                <option value="READER">
                            Standart User
            </option>
                                <option value="REPORTER">
                            Report User
            </option>
            </select>
           

</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldDateLastAccess">
    <label for="MPWSControlDateTimeDateLastAccessID">
        <span class="MPWSText">Last Visit</span>
    </label>
</div>
                            
    
<div class="MPWSControlField MPWSControlFieldDateTime MPWSControlFieldDateTimeDateLastAccess">
    
    
    
    <input id="MPWSControlDateTimeDateLastAccessID" type="text" name="mpws_field_datelastaccess" value="" size="
Notice: Undefined index: _controlSize in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/0e0a8b4e18cb941db66afce81ddbbb8a7cf26093.file.simpleControlDateTime.html.cache.php on line 59

Notice: Trying to get property of non-object in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/0e0a8b4e18cb941db66afce81ddbbb8a7cf26093.file.simpleControlDateTime.html.cache.php on line 59
" class="MPWSControl MPWSControlDateTime MPWSControlDateTimeDateLastAccess">
           
</div>
    

</div>
                    

<div class="MPWSComponent MPWSComponentDataBaseField">


<div class="MPWSFieldLabel MPWSFieldLabelDataEditorSystemUsersFieldDateCreated">
    <label for="MPWSControlDateTimeDateCreatedID">
        <span class="MPWSText">Added Since</span>
    </label>
</div>
                            
    
<div class="MPWSControlField MPWSControlFieldDateTime MPWSControlFieldDateTimeDateCreated">
    
    
    
    <input id="MPWSControlDateTimeDateCreatedID" type="text" name="mpws_field_datecreated" value="" size="
Notice: Undefined index: _controlSize in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/0e0a8b4e18cb941db66afce81ddbbb8a7cf26093.file.simpleControlDateTime.html.cache.php on line 59

Notice: Trying to get property of non-object in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/0e0a8b4e18cb941db66afce81ddbbb8a7cf26093.file.simpleControlDateTime.html.cache.php on line 59
" class="MPWSControl MPWSControlDateTime MPWSControlDateTimeDateCreated">
           
</div>
    

</div>
                
<div class="MPWSControlField MPWSControlFieldFormButtons MPWSControlField
Notice: Undefined index: _controlCssNameCustom in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/c57828bfbc5d080e08a984bc662a85a828b22044.file.simpleFormButtons.html.cache.php on line 36

Notice: Trying to get property of non-object in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/c57828bfbc5d080e08a984bc662a85a828b22044.file.simpleFormButtons.html.cache.php on line 36
">
            <button id="MPWSControlFormButtonsSaveID" type="submit" name="do" value="Save" class="MPWSControl MPWSControlFormButtons MPWSControlFormButtonsSave">
            Save
        </button>
            <button id="MPWSControlFormButtonsPreviewID" type="submit" name="do" value="Preview" class="MPWSControl MPWSControlFormButtons MPWSControlFormButtonsPreview">
            Preview
        </button>
            <button id="MPWSControlFormButtonsCancelID" type="submit" name="do" value="Cancel" class="MPWSControl MPWSControlFormButtons MPWSControlFormButtonsCancel">
            Cancel
        </button>
    </div>
        
    
    </form>
    
</div><?php }} ?>