<?php /*%%SmartyHeaderCode:53139332850788f6087f673-05473734%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b43d79ad5db39a467ed90062c78be6d51ad27400' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1350333413,
      2 => 'file',
    ),
    '2a03f7de45bba1c8c45dc57b650e008e821534f0' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/widgetSummary.html',
      1 => 1350333464,
      2 => 'file',
    ),
    '8353ed817f3a10aa18cb6a40909f78201e516c64' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/searchBox.html',
      1 => 1350338826,
      2 => 'file',
    ),
    'c99e47becdd382321c108454bf032312bfeb8885' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHeader.html',
      1 => 1350327225,
      2 => 'file',
    ),
    'cbb4b263bc8184a28cbef90069561b5b13fa096e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1350335320,
      2 => 'file',
    ),
    'df8c830f715dc57a9d4bd836a9b95a7bcf8da5f7' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/divRowLabelValue.html',
      1 => 1350327225,
      2 => 'file',
    ),
    'd9294aed7ad6b1191011e1b5d20caadea91ec75e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTableTopActions.html',
      1 => 1350327225,
      2 => 'file',
    ),
    '89315d90c92cff95f5c3b5148e051f8e4fa0af29' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html',
      1 => 1350327225,
      2 => 'file',
    ),
    '67ea9f35dff21e59593cdbd7ee49a730e06da33f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html',
      1 => 1350327225,
      2 => 'file',
    ),
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1350327225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53139332850788f6087f673-05473734',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_507c8a50bdd0f5_24586631',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_507c8a50bdd0f5_24586631')) {function content_507c8a50bdd0f5_24586631($_smarty_tpl) {?>



<div id="MPWSWidgetSystemUsersID" class="MPWSWidget MPWSWidgetSystemUsers">
    





<div class="MPWSComponent MPWSComponentWidgetSummary">
    <span class="MPWSText MPWSTextTitle">Active Users</span>
    <span class="MPWSText MPWSTextDetails">List of all active users</span>
    </div>

    <div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <div class="MPWSComponentHeader">
    <h3>Simple Search Box</h3>
</div>
    <div class="MPWSComponentBody">
        <form action="" class="MPWSForm MPWSFormSearchBox" method="POST">
            <div class="MPWSFormFields">
                                                            <div class="MPWSFormField MPWSFormFieldName">
                    <label class="MPWSFieldLabel">Name</label>
                    <input type="text" class="MPWSTextBox" name="searchbox_users_Name" value="test3" placeholder="... part of title"/>
                </div>
                <div class="MPWSSeparator"></div>
                        </div>
            <div class="MPWSBlock MPWSBlockFormControls">
                <input type="submit" name="do" value="Search"/>
                        </div>
        </form>
    </div>
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
    <span class="MPWSValue">                                                <a href="?plugin=toolbox&display=users&sort=Name.asc#MPWSComponenQuickFilteringID" class="MPWSLink">
            lower to higher
        </a>
    </span>
</div>

        
            
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">        Show 
        Last Visit
         from 
    </span>
    <span class="MPWSValue">                                                <a href="?plugin=toolbox&display=users&sort=DateLastAccess.asc#MPWSComponenQuickFilteringID" class="MPWSLink">
            lower to higher
        </a>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-10-16 00:41:29</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow3">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=4" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=4" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=4" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow4">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=5" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=5" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=5" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow5">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=6" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=6" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=6" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow6">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=7" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=7" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=7" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow7">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=8" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=8" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=8" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow8">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=9" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=9" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=9" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow9">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=10" class="MPWSLink" title="Edit" mpws-action="edit" mpws-realm="rowaction" title="Edit">
            Edit
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=10" class="MPWSLink" title="View" mpws-action="view" mpws-realm="rowaction" title="View">
            View
        </a>


                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                


	
	
                            

	
			
	
	            	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=10" class="MPWSLink" title="Remove" mpws-action="delete" mpws-realm="rowaction" title="Remove">
            Remove
        </a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
        
    </div>

</div>

    <div id="MPWSComponenPagingBarID" class="MPWSComponent MPWSComponenPagingBar">
    
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
        <a href="?plugin=toolbox&display=users&pg=1" class="MPWSLink MPWSLinkPaging">First</a>
        <a href="?plugin=toolbox&display=users&pg=2" class="MPWSLink MPWSLinkPaging">>></a>
        <a href="?plugin=toolbox&display=users&pg=3" class="MPWSLink MPWSLinkPaging">Last</a>
        </div>
    
    <div class="MPWSBlock MPWSBlockPageLinks">
        <a href="?plugin=toolbox&display=users&pg=1" class="MPWSLink MPWSLinkPaging">1</a>
        <a href="?plugin=toolbox&display=users&pg=2" class="MPWSLink MPWSLinkPaging">2</a>
        <a href="?plugin=toolbox&display=users&pg=3" class="MPWSLink MPWSLinkPaging">3</a>
        </div>
    
</div>

</div><?php }} ?>