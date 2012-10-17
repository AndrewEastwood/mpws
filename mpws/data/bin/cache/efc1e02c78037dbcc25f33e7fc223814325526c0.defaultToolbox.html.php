<?php /*%%SmartyHeaderCode:702542075507ba5df277520-87354648%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efc1e02c78037dbcc25f33e7fc223814325526c0' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/layout/defaultToolbox.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'f169ec851061427f63366f55936d49966dc76cb1' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1350280496,
      2 => 'file',
    ),
    '9e03eb50762ee8db5b6bfbd19c705caf4faed506' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pageDispatcher.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '5f4bd6ce7e249c98908a9aa0a02239951c907430' => 
    array (
      0 => '/var/www/mpws/web/customer/toolbox/template/page/tools.html',
      1 => 1349945264,
      2 => 'file',
    ),
    'cebc13c56aeef57820b1c2b082b99b20c4f75c99' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/widgetGrabber.html',
      1 => 1349945264,
      2 => 'file',
    ),
    'dfeef937479aa2f13cd1657eea655744ed1498d8' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/system.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '929cbd1ddf297f9edb80d79f7d4b55ebae01ad54' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/systemUserInfo.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '26808fba4af343cf50e3a56bc5670443facea7d8' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/divRowLabelValue.html',
      1 => 1349945264,
      2 => 'file',
    ),
    'fa3ac31b0451d94d3da0caded88fd2ba177c2310' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '0254c4e72400344620e314523c4812fb6574bcb0' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/message.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'aeaf422e55ada4a1ec11d8bb75aa496f13696ff0' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/standartSystemPageStyle1.html',
      1 => 1349945264,
      2 => 'file',
    ),
    'cd33b4b0e2534893b619a4efbe9e33a6eea00cf6' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/menu.html',
      1 => 1350369429,
      2 => 'file',
    ),
    'd0b85a9247966b25bd2cb88ee9ba5b7a90faed9b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/page/standartPublicPageStyle1.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '3c3cdeb9c5b37312bca6c3c4d198cf1985f5226b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pageHeader.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '6f351b33933fd3cab08081271d1ff0192b3d7b9f' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/logo.html',
      1 => 1349945264,
      2 => 'file',
    ),
    'bc7728ca62f9b2e19f11d332f240db36cc846e6e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataElements.html',
      1 => 1350366116,
      2 => 'file',
    ),
    '9cc00798e5f470a0c032087f334f87841c20079c' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pageContent.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '4b7e4c039bbb2894e2f7ce18033096a0a749b700' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pageFooter.html',
      1 => 1349945264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '702542075507ba5df277520-87354648',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ed9f9bbc479_06563165',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ed9f9bbc479_06563165')) {function content_507ed9f9bbc479_06563165($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>
    Toolbox
     - 
    Tools
</title>
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <link rel="stylesheet" type="text/css" href="/static/mpwsDefault.css">
    <script type="text/javascript" src="/static/mpwsDefault.js"></script>
    
    
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
        mpws.action = 'default';
    </script>
    
    <meta name="locale" content="en_us">
</head>
<body>

<div class="MPWSLayout MPWSLayoutDefaultSystem">

            





            
            

    



 

<div id="MPWSPageStandartPublicToolsUsersID" class="MPWSPage MPWSPageStandartPublicPageStyle1 MPWSPageDisplayUsers MPWSPageTools">
    <div class="MPWSComponent MPWSComponenHeader">

	<div class="MPWSComponent MPWSComponentLogo">
    <a href="http://www.google.com" target="blank" class="MPWSLink">
        <img src="/static/mpws_logo_web.png" alt="Logo" class="MPWSImage MPWSImageLogo">
    </a>
</div>

<div class="MPWSComponent MPWSComponentDataElements">
    
                        <div id="MPWSWidgetSystemUserInfoID" class="MPWSWidget MPWSWidgetSystemUserInfo">
    <form action="" method="POST" class="MPWSForm">
        
        <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">You are signed in as:</span>
    <span class="MPWSValue">test3</span>
</div>
        
        <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Last access:</span>
    <span class="MPWSValue">2012-10-17 18:12:44</span>
</div>
        
        
        
        <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Your home page link is:</span>
    <span class="MPWSValue">
<a href="http://www.google.com" target="_self" class="MPWSLink" title="toolbox">toolbox</a></span>
</div>
        <button type="submit" name="do" value="logout" class="MPWSButton">Logout</button>
    </form>
</div>
                    







<div class="MPWSComponent MPWSComponenMenu">

        
            <ul class="MPWSList MPWSListMenu">
            
            <li class="MPWSListItem">
                        <a href="/page/index.html" target="_self" class="MPWSLink" title="Home Page">
                <span class="MPWSText MPWSTextTitle">Home Page</span>
                            </a>
                    </li>
            <li class="MPWSListItem">
                        <a href="/page/dashboard.html" target="_self" class="MPWSLink" title="Dashboard">
                <span class="MPWSText MPWSTextTitle">Dashboard</span>
                            </a>
                    </li>
            <li class="MPWSListItem">
                        <a href="/page/tools.html" target="_self" class="MPWSLink" title="Tools">
                <span class="MPWSText MPWSTextTitle">Tools</span>
                            </a>
                        <ul class="MPWSList MPWSListMenuSub MPWSListMenuSub1">
                                    <li class="MPWSListItem">
                        <a href="/page/tools.html?plugin=toolbox" class="MPWSLink" title="Toolbox">
                            <span class="MPWSText">Toolbox</span>
                        </a>
                                                                            
    




    


        
            <ul class="MPWSList MPWSListMenuSub MPWSListMenuSub2">
            
            <li class="MPWSListItem">
                        <a href="/page/tools.html?plugin=toolbox&display=users" target="_self" class="MPWSLink" title="User Manager">
                <span class="MPWSText MPWSTextTitle">User Manager</span>
                            </a>
                    </li>
        </ul>
        

                                            </li>
                            </ul>
                    </li>
            <li class="MPWSListItem">
                        <a href="/page/messages.html" target="_self" class="MPWSLink" title="Web Messages">
                <span class="MPWSText MPWSTextTitle">Web Messages</span>
                            </a>
                    </li>
            <li class="MPWSListItem">
                        <a href="/page/help.html" target="_self" class="MPWSLink" title="Help">
                <span class="MPWSText MPWSTextTitle">Help</span>
                            </a>
                    </li>
        </ul>
        
</div>

            </div>

</div>
    <div class="MPWSComponent MPWSComponenContent">

<div class="MPWSComponent MPWSComponentDataElements">
    
                            <div id="MPWSComponentMessageCommonID" class="MPWSComponent MPWSComponentMessage MPWSComponentMessageCommon">
            <ul class="MPWSList">
                    <li class="MPWSListItem">Hello World!!!!!</li>
                </ul>
        </div>

                    


<div class="MPWSComponent MPWSComponentWidgetGrabber">
    <div class="MPWSSpacer MPWSWidgetSpace MPWSSpacerBefore"></div>
    


<div id="MPWSWidgetDataTableViewSystemUsersID" class="MPWSWidget MPWSWidgetDataTableView MPWSWidgetDataTableViewSystemUsers">
    





<div class="MPWSComponent MPWSComponentWidgetSummary">
    <span class="MPWSText MPWSTextTitle">Active Users</span>
    <span class="MPWSText MPWSTextDetails">List of all active users</span>
    </div>

    

<div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <div class="MPWSComponentHeader">
    <h3>Simple Search Box</h3>
</div>

    <form action="" class="MPWSForm MPWSFormSearchBox" method="POST">
        
                                            

        
<div class="MPWSComponent MPWSComponentField MPWSRenderModeNormal">


<div class="MPWSFieldLabel MPWSFieldLabelDataTableViewSystemUsersSearchBoxFieldName">
    <label for="MPWSControlTextBoxNameID">
        <span class="MPWSText">User Name</span>
    </label>
</div>
                    

            


<div class="MPWSControlField MPWSControlFieldTextBox MPWSControlFieldTextBoxName MPWSControlRenderModeNormal">
    
    
    
    <input id="MPWSControlTextBoxNameID" type="text" name="mpws_field_name" value="" size="25" maxlength="" class="MPWSControl MPWSControlTextBox MPWSControlTextBoxName">
    
    
</div>
    

</div>
                

                        
<div class="MPWSControlField MPWSControlFieldFormButtons">
            <button id="MPWSControlFormButtonsSearchID" type="submit" name="do" value="Search" class="MPWSControl MPWSControlButtonSearch">
            Search
        </button>
    </div>
            </form>

        
</div>

    
<div id="MPWSComponenQuickFilteringID" class="MPWSComponent MPWSComponenQuickFiltering">
        <div class="MPWSComponentHeader">
    <h3>Quick Data Filtering</h3>
</div>
        
            
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">        Show 
        User Names
         from 
    </span>
    <span class="MPWSValue">                                                
        
<a href="?plugin=toolbox&display=users&oid=2&sort=Name.asc#MPWSComponenQuickFilteringID" target="_self" class="MPWSLink" title="lower to higher">lower to higher</a>
    </span>
</div>

        
            
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">        Show 
        Last Visit
         from 
    </span>
    <span class="MPWSValue">                                                
        
<a href="?plugin=toolbox&display=users&oid=2&sort=DateLastAccess.asc#MPWSComponenQuickFilteringID" target="_self" class="MPWSLink" title="lower to higher">lower to higher</a>
    </span>
</div>

    </div>

    

<div id="MPWSComponentDataTableTopActionsID" class="MPWSComponent MPWSComponentDataTableTopActions">



    
                        


	
	
                            

	
			
	
	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=new" class="MPWSLink" title="Add New Record" mpws-action="new" mpws-realm="topaction" title="Add New Record">
            Add New Record
        </a>


            
</div>
    

<div id="MPWSComponentDataTableID" class="MPWSComponent MPWSComponentDataTable">






    <div class="MPWSDataTableRows">
    
            <div class="MPWSDataTableRow MPWSDataTableRowCaptions">


        
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
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=1" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=1" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=1" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow1">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=2" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=2" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=2" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">olololo</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">0000-00-00 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow2">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=3" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=3" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=3" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">test3</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-10-17 19:04:24</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow3">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=13" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=13" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=13" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">enabledu</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">0000-00-00 00:00:00</div>
                            </div>
        
    </div>

</div>

    <div id="MPWSComponenPagingBarID" class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Filtered Records:</span>
    <span class="MPWSValue">4</span>
</div>
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Total Records:</span>
    <span class="MPWSValue">4</span>
</div>

    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Table Always Shows:</span>
    <span class="MPWSValue">10</span>
</div>
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Total Pages:</span>
    <span class="MPWSValue">1</span>
</div>
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Current Page:</span>
    <span class="MPWSValue">1</span>
</div>

    <div class="MPWSWrapper">
        
        
    
    </div>
    
</div>

</div>
    <div class="MPWSSpacer MPWSWidgetSpace MPWSSpacerAfter"></div>
</div>
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