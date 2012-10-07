<?php /*%%SmartyHeaderCode:13723699650716ea2589fe0-63295964%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1349291075,
      2 => 'file',
    ),
    '62e33b07ec504086a62347b14b2fbbdeaa4083e5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pageDispatcher.html',
      1 => 1349287656,
      2 => 'file',
    ),
    '48a01374c6045ce1f4ded1f4efca0a227fdf65db' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/page/dashboard.html',
      1 => 1349287120,
      2 => 'file',
    ),
    'c58f0a31db40716d1815e6896038e85e3e434619' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetGrabber.html',
      1 => 1349038208,
      2 => 'file',
    ),
    'd6d46df5db445da56cb54532472df58a57c03f2a' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/page/system.html',
      1 => 1349287120,
      2 => 'file',
    ),
    '69efce9731aeeb54451ddefc0c66f864c1ae09b4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1349287535,
      2 => 'file',
    ),
    '4b6907d3d72d2c63144539dc3dd7241049b78f4d' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/message.html',
      1 => 1349287120,
      2 => 'file',
    ),
    '944f420f2f1d1265051400f0aa58ed80b23a7476' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/systemPluginLinkList.html',
      1 => 1349375490,
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
      1 => 1349288366,
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
  'nocache_hash' => '13723699650716ea2589fe0-63295964',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50717426e83984_78310237',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50717426e83984_78310237')) {function content_50717426e83984_78310237($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>MPWS Toolbox - dashboard</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="/static/toolboxDisplay.css">
    <script type="text/javascript" src="/static/toolboxAction.js"></script>
    
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});
        // security token
        mpws.token = 'dc7161be3dbf2250c8954e560cc35060';
        // page
        mpws.page = 'dashboard';
        // display
        mpws.display = '';
        // action
        mpws.action = 'default';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutToolbox">

            






    
    

    



 

<div class="MPWSPage MPWSPageDisplay MPWSPageStandartPublicPageStyle1 MPWSPageStandartPublic
Notice: Undefined index: DISPLAY in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/fd493ce1d9a8de3661d7b6fa94771c833d559aee.file.standartPublicPageStyle1.html.cache.php on line 33
" id="MPWSPageStandartPublic
Notice: Undefined index: PAGE in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/fd493ce1d9a8de3661d7b6fa94771c833d559aee.file.standartPublicPageStyle1.html.cache.php on line 34
ID">
    <div class="MPWSComponent MPWSComponenHeader">

	<div class="MPWSComponent MPWSComponentLogo">
    <a href="http://www.google.com" target="blank" class="MPWSLink">
        <img src="/static/toolbox_logo.gif" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div>

<div class="MPWSComponent MPWSComponentDataElements">
    
                        <div class="MPWSBlock"><div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">You are signed in as:</span>
            <span class="MPWSValue">test3</span>
        </div><div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Last access:</span>
            <span class="MPWSValue"></span>
        </div>
        <div class="MPWSLabelValueRow">
            <span class="MPWSLabel">Your home page link is:</span>
            <span class="MPWSValue"><a href="
Notice: Undefined index: SITE in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/69efce9731aeeb54451ddefc0c66f864c1ae09b4.file.systemUserInfo.html.cache.php on line 45

Notice: Trying to get property of non-object in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/69efce9731aeeb54451ddefc0c66f864c1ae09b4.file.systemUserInfo.html.cache.php on line 45

Notice: Trying to get property of non-object in /media/sda3/Develop/github/web/mpws/data/bin/templates_c/69efce9731aeeb54451ddefc0c66f864c1ae09b4.file.systemUserInfo.html.cache.php on line 45
" target="blank" class="MPWSLink">toolbox</a></span>
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
                    <div class="MPWSBlock"><div id="MPWSWidgetSystemPluginLinkListID" class="MPWSWidget MPWSWidgetSystemPluginLinkList">
    <ul class="MPWSList MPWSListPluginLinks">
            <li class="MPWSListItem MPWSListItemPluginLink">
            <a href="/page/tools.html?plugin=toolbox" class="MPWSLink" title="Toolbox">
                <div class="MPWSMiniBlock">
                    <div class="MPWSWrapper">
                        <span class="MPWSText MPWSTextTitle">Toolbox</span>
                        <span class="MPWSText MPWSTextDescription">System Toolbox Manager</span>
                        <span class="MPWSText MPWSTextLink">Start here</span>
                    </div>
                </div>
            </a>
        </li>
        </ul>
</div></div>
                    <div class="MPWSBlock">



<div class="MPWSComponent MPWSComponentWidgetGrabber">
    <div clas="MPWSWidget">


    <div class="MPWSComponent MPWSComponentSearchBox">
    <div class="MPWSComponentHeader">
        <h3>Search Box</h3>
    </div>
    <div class="MPWSComponentBody">
        <form action="" class="MPWSForm MPWSFormSearchBox" method="POST">
            <div class="MPWSFormFields">
                                            <div class="MPWSFormField MPWSFormFieldName">
                    <label class="MPWSFieldLabel">Name</label>
                    <input type="text" class="MPWSTextBox" name="searchbox_users_Name" value="" placeholder="... part of title"/>
                </div>
                <div class="MPWSSeparator"></div>
                        </div>
            <div class="MPWSBlock MPWSBlockFormControls">
                <input type="submit" name="do" value="Search"/>
                        </div>
        </form>
    </div>
    </div>

	QF

    

<div class="MPWSComponent MPWSComponentDataTable">






<div class="MPWSComponent MPWSComponentObjectSummary">
	<span class="MPWSText MPWSTextTitle">Active Users</span>
	<span class="MPWSText MPWSTextDetails">List of all active users</span>
</div>






    
            <div class="MPWSDataTableRow MPWSDataTableRowCaptions">


                    <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellActions">Actions</div>
        
                                    <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellName">
                    User Name
                </div>
                            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellActive">
                    Active
                </div>
                            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellDatelastaccess">
                    Last Time Visit
                </div>
                            </div>
    
            <div class="MPWSDataTableRow MPWSDataTableRow0">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=24" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=24" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=24" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow1">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=23" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=23" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=23" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow2">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=22" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=22" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=22" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow3">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=21" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=21" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=21" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow4">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=20" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=20" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=20" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow5">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=19" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=19" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=19" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow6">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=18" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=18" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=18" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow7">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=17" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=17" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=17" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow8">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=16" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=16" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=16" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow9">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=edit&amp;oid=15" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
		EDIT
	</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=view&amp;oid=15" class="MPWSLink" target="" mpws-action="view" title="VIEW">
		VIEW
	</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

			
	
				
	
	

	<a href="/page/tools.html?plugin=toolbox&amp;display=&amp;action=delete&amp;oid=15" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
		REMOVE
	</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
    
</div>

    <div class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSBlock MPWSBlockSummary">
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Filtered Records:</label>
            <span class="MPWSValue">24</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Total Records:</label>
            <span class="MPWSValue">24</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Table Always Shows:</label>
            <span class="MPWSValue">10</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Total Pages:</label>
            <span class="MPWSValue">3</span>
        </div>
        <div class="MPWSDataRow">
            <label class="MPWSLabel">Current Page:</label>
            <span class="MPWSValue">1</span>
        </div>
    </div>
    
    <div class="MPWSBlock MPWSBlockEdgeLinks">
        <a href="?pg=1" class="MPWSLink MPWSLinkPaging">FIRST</a>
        <a href="?pg=2" class="MPWSLink MPWSLinkPaging">NEXT</a>
        <a href="?pg=3" class="MPWSLink MPWSLinkPaging">LAST</a>
        </div>
    
    <div class="MPWSBlock MPWSBlockPageLinks">
        <a href="?pg=1" class="MPWSLink MPWSLinkPaging">1</a>
        <a href="?pg=2" class="MPWSLink MPWSLinkPaging">2</a>
        <a href="?pg=3" class="MPWSLink MPWSLinkPaging">3</a>
        </div>
    
</div>
</div>
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