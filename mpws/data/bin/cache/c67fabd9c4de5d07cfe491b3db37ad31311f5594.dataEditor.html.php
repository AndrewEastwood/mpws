<?php /*%%SmartyHeaderCode:24336973850804473ba3d19-47251449%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c67fabd9c4de5d07cfe491b3db37ad31311f5594' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1350933229,
      2 => 'file',
    ),
    '9a8ad909480ac298824cde057ccb3611e91b9ea5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/title.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '1d9bc5d7a3d04d87c716664e439f92529a65f6f2' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/messageList.html',
      1 => 1350663924,
      2 => 'file',
    ),
    'f9d26ffe8808e52db1aad649635991eda29ac324' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/header.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '497c121d4947324a67febd0f320f0a2ae4da1ea4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/trigger/control.html',
      1 => 1350930131,
      2 => 'file',
    ),
    'dca5dd749b18945338a219bc2f54ca85de638484' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlTextBox.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '1e3b7818f0fcb662e00f52afdfc4dc5773a1837f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/label.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '678a2241579c9b6fb365ece15a5c120b10da8cc1' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlCheckBox.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '321ce9d7bbf041e8ae148d931be9e86a15daee3d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlTextArea.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '139ad89f33c61fbb4479f7ad4755b741d4601107' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlDropDown.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '01ac54aedbbf6a7f49e46e1954b36f6473958844' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '1f41ac3515949000dfb743e53eb00b074a6cc20f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1350931862,
      2 => 'file',
    ),
    '535697c6004dc371263dc35eb2c3e9633bf84300' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/link.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24336973850804473ba3d19-47251449',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5085b3282338d6_57088539',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5085b3282338d6_57088539')) {function content_5085b3282338d6_57088539($_smarty_tpl) {?>
<div id="MPWSWidgetDataEditorSystemUsersID" class="MPWSWidget MPWSWidgetDataEditor MPWSWidgetDataEditorSystemUsers">
    
    
    
            




<div class="MPWSComponent MPWSComponentTitle">
    <span class="MPWSText MPWSTextTitle">User Editor</span>
    <span class="MPWSText MPWSTextDetails">You can edit user settings</span>
        <span class="MPWSText MPWSTextCustom">Adding new record</span>
    </div>
        
    
            <div class="MPWSComponent MPWSComponentMessageList MPWSComponentMessageListDataEditorValidator">
<div class="MPWSHeader">
    <h3>We have encountered the following issue(s):</h3>
</div>
    <ul class="MPWSList">
            <li class="MPWSListItem">Check entered value in the field Name</li>
        </ul>
</div>
        
    
    <form action="" name="data_edit_systemusers" method="POST" class="MPWSForm MPWSFormEditor MPWSFormEditorPageEdit">
        <div class="MPWSFormBody">

    
    
        
        
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


    
    

<label for="MPWSControlTextBoxNameID">
    <span class="MPWSText">User Name</span>
</label>
                    

    


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxName MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxNameID" type="text" name="mpws_field_name" value="" size="25" maxlength="100" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxName">
    
    
</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


    
    

<label for="MPWSControlTextBoxPasswordID">
    <span class="MPWSText">User Password</span>
</label>
                    

    


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxPassword MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxPasswordID" type="text" name="mpws_field_password" value="" size="25" maxlength="32" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxPassword">
    
    
</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


    
    

<label for="MPWSControlCheckBoxActiveID">
    <span class="MPWSText">Check if user is enabled</span>
</label>
                    

    


<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxActive MPWSControlRenderModeNormal">

            
                    <input id="MPWSControlCheckBoxActiveID" type="checkbox" name="mpws_field_active" unchecked class="MPWSControl MPWSControlCheckBox MPWSControlCheckBoxActive">
            

</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


    
    

<label for="MPWSControlTextAreaPermisionsID">
    <span class="MPWSText">Access Level</span>
</label>
                    
    
<div class="MPWSControlField MPWSControlFieldTextArea MPWSControlFieldTextAreaPermisions MPWSControlRenderModeNormal">
    
    
    <textarea id="MPWSControlTextAreaPermisionsID" name="mpws_field_permisions" cols="45" rows="6" class="MPWSControl MPWSControlTextArea MPWSControlTextAreaPermisions">Toolbox:*:all;
Writer:*:all;</textarea>
           
</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


    
    

<label for="MPWSControlDropDownRoleID">
    <span class="MPWSText">User Role</span>
</label>
                    
        
<div class="MPWSControlField MPWSControlFieldDropDown MPWSControlFieldDropDownRole MPWSControlRenderModeNormal">
    
    
    
    
    <select id="MPWSControlDropDownRoleID" type="checkbox" name="mpws_field_role" class="MPWSControl MPWSControlDropDown MPWSControlDropDownRole">
                                <option value="SUPERADMIN">
                            Administrator
            </option>
                                <option value="READER" selected="selected">
                            Standart User
            </option>
                                <option value="REPORTER">
                            Report User
            </option>
            </select>
           

</div>
        
     
     
     
     
     

</div>
                        
            
        
            
        
    
            </div>
    
        <div class="MPWSFormFooter">
    
    

<div class="MPWSControlField MPWSControlFieldFormButtons">
            <button id="MPWSControlFormButtonsSaveID" type="submit" name="do" value="Save" class="MPWSControl MPWSControlButtonSave">
            Save
        </button>
            <button id="MPWSControlFormButtonsPreviewID" type="submit" name="do" value="Preview" class="MPWSControl MPWSControlButtonPreview">
            Preview
        </button>
            <button id="MPWSControlFormButtonsCancelID" type="submit" name="do" value="Cancel" class="MPWSControl MPWSControlButtonCancel">
            Cancel
        </button>
    </div>
        </div>
    
    </form>

    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
         
        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


    
            
                

    



<a href="/page/tools.html?plugin=toolbox&display=users" target="" class="MPWSLink" title="Back To Records" Array>Back To Records</a>

    

</div>
    </div>

</div><?php }} ?>