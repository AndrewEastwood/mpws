<?php /*%%SmartyHeaderCode:146328702350788f60d89873-89011730%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1349894052,
      2 => 'file',
    ),
    '62e33b07ec504086a62347b14b2fbbdeaa4083e5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageDispatcher.html',
      1 => 1349287656,
      2 => 'file',
    ),
    '6a2bd7a905030f8591c53d6372450316d46fb9b7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/tools.html',
      1 => 1349821193,
      2 => 'file',
    ),
    'c58f0a31db40716d1815e6896038e85e3e434619' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetGrabber.html',
      1 => 1349809493,
      2 => 'file',
    ),
    'd6d46df5db445da56cb54532472df58a57c03f2a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/system.html',
      1 => 1349894101,
      2 => 'file',
    ),
    '69efce9731aeeb54451ddefc0c66f864c1ae09b4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1349812140,
      2 => 'file',
    ),
    'df8c830f715dc57a9d4bd836a9b95a7bcf8da5f7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/divRowLabelValue.html',
      1 => 1349810994,
      2 => 'file',
    ),
    '26c70ee4d97bcc8ae6e6d1f89d9932f6acdb265b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1349812898,
      2 => 'file',
    ),
    '4b6907d3d72d2c63144539dc3dd7241049b78f4d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/message.html',
      1 => 1349287120,
      2 => 'file',
    ),
    'fee379ac26415e5d616f64f7c05de0bc03ae5909' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartSystemPageStyle1.html',
      1 => 1349287675,
      2 => 'file',
    ),
    'd0b60fd847c51d05aceafa98d1fbb45732576e1e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menu.html',
      1 => 1349290714,
      2 => 'file',
    ),
    'b2768aa8e09878d7f6f1826a659bc1072bf3fe70' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/menuPlugins.html',
      1 => 1349290726,
      2 => 'file',
    ),
    'fd493ce1d9a8de3661d7b6fa94771c833d559aee' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/standartPublicPageStyle1.html',
      1 => 1349810070,
      2 => 'file',
    ),
    'aa0374ee876c63be03d83fc175f0ba951ad8b0aa' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageHeader.html',
      1 => 1349288426,
      2 => 'file',
    ),
    '0acc49945e64837379fb78db2aad4b0b21c1615a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/logo.html',
      1 => 1349288503,
      2 => 'file',
    ),
    '531a0bfa0ec2c65a065a6298b73205faca29dadf' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataElements.html',
      1 => 1349022504,
      2 => 'file',
    ),
    '64541c85ffcbf963acb20a5522dfc2653e5fd027' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageContent.html',
      1 => 1349288438,
      2 => 'file',
    ),
    'fc6ae292a0740dd0f3e299c3741739a1487bf763' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageFooter.html',
      1 => 1349288441,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '146328702350788f60d89873-89011730',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5078b2866440a6_80049343',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5078b2866440a6_80049343')) {function content_5078b2866440a6_80049343($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - tools</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = '4a931512ce65bdc9ca6808adf92d8783';
        // page
        mpws.page = 'tools';
        // display
        mpws.display = 'users';
        // action
        mpws.action = 'new';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

            





            
            

    



 

<div id="MPWSPageStandartPublicToolsUsersID" class="MPWSPage MPWSPageStandartPublicPageStyle1 MPWSPageDisplayUsers MPWSPageTools">
    <div class="MPWSComponent MPWSComponenHeader">

	<div class="MPWSComponent MPWSComponentLogo">
    <a href="http://www.google.com" target="blank" class="MPWSLink">
        <img src="/static/toolbox_logo.gif" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div>

<div class="MPWSComponent MPWSComponentDataElements">
    
                        <div class="MPWSBlock"><div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        
        <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">You are signed in as:</span>
    <span class="MPWSValue">test3</span>
</div>
        
        <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Last access:</span>
    <span class="MPWSValue">2012-10-13 02:22:37</span>
</div>
        
        
        
        <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Your home page link is:</span>
    <span class="MPWSValue">
<a href="http://www.google.com" target="_self" class="MPWSLink" title="toolbox">toolbox</a></span>
</div>
        <button type="submit" name="do" value="logout" class="MPWSButton">Logout</button>
    </form>
</div></div>
                    <div class="MPWSBlock">




<div class="MPWSComponent MPWSComponenMenu">
        <ul class="MPWSList MPWSListMenu">
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/index.html" target="_self" class="MPWSLink" title="Home Page">
                <span class="MPWSText MPWSTextTitle">Home Page</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/dashboard.html" target="_self" class="MPWSLink" title="Dashboard">
                <span class="MPWSText MPWSTextTitle">Dashboard</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/tools.html" target="_self" class="MPWSLink" title="Tools">
                <span class="MPWSText MPWSTextTitle">Tools</span>
                            </a>
                            <div class="MPWSComponent MPWSComponenMenuPlugins">
    <ul class="MPWSList MPWSListPluginLinks">
            <li class="MPWSListItem MPWSListItemPluginLink">
            <a href="/page/tools.html?plugin=toolbox" class="MPWSLink" title="Toolbox">
                <span class="MPWSText MPWSTextTitle">Toolbox</span>
                                                    
    



<div class="MPWSComponent MPWSComponenMenu">
        <ul class="MPWSList MPWSListMenu">
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/tools.html?plugin=toolbox&display=users" target="_self" class="MPWSLink" title="User Manager">
                <span class="MPWSText MPWSTextTitle">User Manager</span>
                            </a>
                    </li>
        </ul>
    </div>
                            </a>
        </li>
        </ul>
</div>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/users.html" target="_self" class="MPWSLink" title="Users">
                <span class="MPWSText MPWSTextTitle">Users</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/messages.html" target="_self" class="MPWSLink" title="Web Messages">
                <span class="MPWSText MPWSTextTitle">Web Messages</span>
                            </a>
                    </li>
            <li class="MPWSListItem MPWSListItemMenu">
                        <a href="/page/help.html" target="_self" class="MPWSLink" title="Help">
                <span class="MPWSText MPWSTextTitle">Help</span>
                            </a>
                    </li>
        </ul>
    </div></div>
            </div>

</div>
    <div class="MPWSComponent MPWSComponenContent">

<div class="MPWSComponent MPWSComponentDataElements">
    
                        <div class="MPWSBlock">    <div id="MPWSComponentMessageCommonID" class="MPWSComponent MPWSComponentMessage MPWSComponentMessageCommon">
            <ul class="MPWSList MPWSListMessages MPWSListMessagesCommon">
                    <li class="MPWSListItem MPWSListItemMessage">Hello World!!!!!</li>
                </ul>
        </div>
</div>
                    <div class="MPWSBlock">


<div class="MPWSComponent MPWSComponentWidgetGrabber">
    <div class="MPWSSpacer MPWSWidgetSpace MPWSSpacerBefore"></div>
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
    
</div>
    <div class="MPWSSpacer MPWSWidgetSpace MPWSSpacerAfter"></div>
</div></div>
            </div>

</div>
    <div class="MPWSComponent MPWSComponenFooter">

<div class="MPWSComponent MPWSComponentDataElements">
    
                </div>

</div>
</div>


    
</div>

</body>
</html><?php }} ?>