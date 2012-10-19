<?php /*%%SmartyHeaderCode:538368150816f8f706e13-81002143%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be7e5e2b15fcfe9553056b2b52a6c64faaf67f44' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/dataEditor.html',
      1 => 1350660542,
      2 => 'file',
    ),
    'a0ddab4075dc8e4fdcb58fc91caa9a6edf3b90dc' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/title.html',
      1 => 1350659758,
      2 => 'file',
    ),
    '47b44db1435148cce8713f2c95e42d447e21da60' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/messageList.html',
      1 => 1350653693,
      2 => 'file',
    ),
    'a9e48bbe01dbc0e9e1875b6afe94b60600cbec79' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/header.html',
      1 => 1350642619,
      2 => 'file',
    ),
    'a291fbfdf58a8c4b968a9b89fa85536efefe03b7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/trigger/control.html',
      1 => 1350652162,
      2 => 'file',
    ),
    '2827081c62f53487a9d79a04da7f97cdee4c78bf' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlTextBox.html',
      1 => 1350627484,
      2 => 'file',
    ),
    '532b187efab104354fd4473020382d17605c376b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/label.html',
      1 => 1350655066,
      2 => 'file',
    ),
    '009fe1df72b76a435ac784ec136536b277d8c53b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlCheckBox.html',
      1 => 1350627484,
      2 => 'file',
    ),
    '44e1e4a41f3888ebb58b0387ec68f35e9ce4fd4b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlTextArea.html',
      1 => 1350627484,
      2 => 'file',
    ),
    '8c8470e77fd2bfc3d0380f4331ff625706cd0448' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlDropDown.html',
      1 => 1350627484,
      2 => 'file',
    ),
    '331b902f5b3f9259cd18347d4cf8618679da3a53' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1350653018,
      2 => 'file',
    ),
    'f53137f2b7fe9fb4a8d2f0e039ef190f3c30373d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/link.html',
      1 => 1350642297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '538368150816f8f706e13-81002143',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5081733150fb83_19098372',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5081733150fb83_19098372')) {function content_5081733150fb83_19098372($_smarty_tpl) {?>
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
            <li class="MPWSListItem">Check entered value in the field Name</li>
        </ul>
</div>
        
    
    <form action="" name="data_edit_systemusers" method="POST" class="MPWSForm MPWSFormEditor MPWSFormEditorPageEdit">
        <div class="MPWSFormBody">

    
    
        
        
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




<label for="MPWSControl0ID">
    <span class="MPWSText">User Name</span>
</label>
                    

    


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxName MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxNameID" type="text" name="mpws_field_name" value="" size="25" maxlength="100" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxName">
    
    
</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




<label for="MPWSControl0ID">
    <span class="MPWSText">User Password</span>
</label>
                    

    


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxPassword MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxPasswordID" type="text" name="mpws_field_password" value="" size="25" maxlength="32" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxPassword">
    
    
</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




<label for="MPWSControl0ID">
    <span class="MPWSText">Check if user is enabled</span>
</label>
                    

    


<div class="MPWSControlField MPWSControlFieldCheckBox MPWSControlFieldCheckBoxActive MPWSControlRenderModeNormal">

            
                    <input id="MPWSControlCheckBoxActiveID" type="checkbox" name="mpws_field_active" unchecked class="MPWSControl MPWSControlCheckBox MPWSControlCheckBoxActive">
            

</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




<label for="MPWSControl0ID">
    <span class="MPWSText">Access Level</span>
</label>
                    
        
<div class="MPWSControlField MPWSControlFieldTextArea MPWSControlFieldTextAreaPermisions MPWSControlRenderModeNormal">
    
    
    <textarea id="MPWSControlTextAreaPermisionsID" name="mpws_field_permisions" cols="45" rows="6" class="MPWSControl MPWSControlTextArea MPWSControlTextAreaPermisions"></textarea>
           
</div>
    

</div>
                        
            
                
                                
                            
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




<label for="MPWSControl0ID">
    <span class="MPWSText">User Role</span>
</label>
                    
        
<div class="MPWSControlField MPWSControlFieldDropDown MPWSControlFieldDropDownRole MPWSControlRenderModeNormal">
    
    
    
    
    <select id="MPWSControlDropDownRoleID" type="checkbox" name="mpws_field_role" class="MPWSControl MPWSControlDropDown MPWSControlDropDownRole">
                                <option value="SUPERADMIN" selected="selected">
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
        
<a href="/page/tools.html?plugin=toolbox&display=users" target="" class="MPWSLink" title="Back To List" >Back To List</a>
    </div>

</div><?php }} ?>