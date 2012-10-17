<?php /*%%SmartyHeaderCode:1458323415507ba5dee02fd9-79385301%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '879f11b0d8058e720165bd0a6f39168e1dbf38d0' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1350452630,
      2 => 'file',
    ),
    '53b89dd6b44306e49c58dbbb2364dc696ec56d5d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/widgetSummary.html',
      1 => 1350366116,
      2 => 'file',
    ),
    '11949475bf39a92dd41d7b3b130e15a87110c9cd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/searchBox.html',
      1 => 1350490114,
      2 => 'file',
    ),
    '2039f98e5dfc8db172dee84db653f217729c83a4' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleHeader.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '3349bdbf34090f0c005554152d092228d9ae2049' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/controlFieldSwitcher.html',
      1 => 1350479945,
      2 => 'file',
    ),
    '895b30db561d11a1f49691acbd28353b8e852c1e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleControlTextBox.html',
      1 => 1350490601,
      2 => 'file',
    ),
    'f91858edc3c80e6c6d6bf864d185f1e815a16972' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350031670,
      2 => 'file',
    ),
    '903d91440aad38a59f6d2af5f4a037f8c1d06e72' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350484464,
      2 => 'file',
    ),
    '1a0890e2fef5427fab806f0d3f45ac8d91d053dd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1350480517,
      2 => 'file',
    ),
    'fa3ac31b0451d94d3da0caded88fd2ba177c2310' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '26808fba4af343cf50e3a56bc5670443facea7d8' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/divRowLabelValue.html',
      1 => 1349945264,
      2 => 'file',
    ),
    '268e7bb972d64a0245341ccff582391497b1b69b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataTableTopActions.html',
      1 => 1350280496,
      2 => 'file',
    ),
    'da36c658b12a320f0d88d5572808687624b18af9' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/actionLink.html',
      1 => 1350280496,
      2 => 'file',
    ),
    '6999ca4e59688c03dcb6f324646871f1d215ed21' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/dataTable.html',
      1 => 1350490242,
      2 => 'file',
    ),
    'e98dc992345d0b598994ca191b79a051180fa317' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/pagingBar.html',
      1 => 1350471184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1458323415507ba5dee02fd9-79385301',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507ed9f9b76968_73208429',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507ed9f9b76968_73208429')) {function content_507ed9f9b76968_73208429($_smarty_tpl) {?>



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

</div><?php }} ?>