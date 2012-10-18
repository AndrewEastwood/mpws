<?php /*%%SmartyHeaderCode:171626206850804394541817-04942144%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b43d79ad5db39a467ed90062c78be6d51ad27400' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1350506009,
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
      1 => 1350583979,
      2 => 'file',
    ),
    'c99e47becdd382321c108454bf032312bfeb8885' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHeader.html',
      1 => 1350586183,
      2 => 'file',
    ),
    'bb64dcf15800472279be847786c5bb40578668b9' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/controlFieldSwitcher.html',
      1 => 1350496351,
      2 => 'file',
    ),
    '569c0239c22ec9c0530c6339abe971e96503f09c' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleControlTextBox.html',
      1 => 1350579434,
      2 => 'file',
    ),
    '03c192a2b7a2808a9e905faf14ec3009c8742fe1' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFieldLabel.html',
      1 => 1350327225,
      2 => 'file',
    ),
    'c57828bfbc5d080e08a984bc662a85a828b22044' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleFormButtons.html',
      1 => 1350494931,
      2 => 'file',
    ),
    'cbb4b263bc8184a28cbef90069561b5b13fa096e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1350494931,
      2 => 'file',
    ),
    '26c70ee4d97bcc8ae6e6d1f89d9932f6acdb265b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHyperlink.html',
      1 => 1350327225,
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
      1 => 1350510148,
      2 => 'file',
    ),
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1350506034,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '171626206850804394541817-04942144',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50806280d9c6e5_18983813',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50806280d9c6e5_18983813')) {function content_50806280d9c6e5_18983813($_smarty_tpl) {?>



<div id="MPWSWidgetDataTableViewSystemUsersID" class="MPWSWidget MPWSWidgetDataTableView MPWSWidgetDataTableViewSystemUsers">
    





<div class="MPWSComponent MPWSComponentWidgetSummary">
    <span class="MPWSText MPWSTextTitle">Active Users</span>
    <span class="MPWSText MPWSTextDetails">List of all active users</span>
    </div>

    

<div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <div class="MPWSComponent MPWSComponentHeader">
    <h3>Simple Search Box</h3>
</div>

    <form action="" class="MPWSForm MPWSFormSearchBox" method="POST">
        <div class="MPWSFormBody">
        
                                                                        

            
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
                </div>
        
        <div class="MPWSFormFooter">
        
                        
<div class="MPWSControlField MPWSControlFieldFormButtons">
            <button id="MPWSControlFormButtonsSearchID" type="submit" name="do" value="Search" class="MPWSControl MPWSControlButtonSearch">
            Search
        </button>
    </div>
                </div>
    </form>

        
</div>

    
<div id="MPWSComponenQuickFilteringID" class="MPWSComponent MPWSComponenQuickFiltering">
        <div class="MPWSComponent MPWSComponentHeader">
    <h3>Quick Data Filtering</h3>
</div>
        
            
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">        Show 
        User Names
         from 
    </span>
    <span class="MPWSValue">                                                
        
<a href="?plugin=toolbox&display=users&sort=Name.asc#MPWSComponenQuickFilteringID" target="_self" class="MPWSLink" title="lower to higher">lower to higher</a>
    </span>
</div>

        
            
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">        Show 
        Last Visit
         from 
    </span>
    <span class="MPWSValue">                                                
        
<a href="?plugin=toolbox&display=users&sort=DateLastAccess.asc#MPWSComponenQuickFilteringID" target="_self" class="MPWSLink" title="lower to higher">lower to higher</a>
    </span>
</div>

    </div>

    

<div id="MPWSComponentDataTableTopActionsID" class="MPWSComponent MPWSComponentDataTableTopActions">



    
                        


	
	
                            

	
			
	
	
	
	        
        
                                        
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=new" class="MPWSLink" title="Add New Record" mpws-action="new" mpws-realm="topaction" title="Add New Record">
            Add New Record
        </a>


            
</div>
    
<div id="MPWSComponentDataTableDataTableViewSystemUsersID" class="MPWSComponent MPWSComponentDataTable MPWSComponentDataTableDataTableViewSystemUsers">






    <div class="MPWSDataTableRows">
    
            <div class="MPWSDataTableRow MPWSDataTableRowCaptions">


        
                                    <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellName">
                    User Name
                </div>
                            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellActive">
                    Active
                </div>
                            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellDateLastAccess">
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">0000-00-00 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-10-18 22:59:41</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
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
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
        
    </div>

</div>

    <div id="MPWSComponenPagingBarID" class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Filtered Records:</span>
    <span class="MPWSValue">24</span>
</div>
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Total Records:</span>
    <span class="MPWSValue">24</span>
</div>

    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Table Always Shows:</span>
    <span class="MPWSValue">10</span>
</div>
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Total Pages:</span>
    <span class="MPWSValue">3</span>
</div>
    
    <div class="MPWSRowLabelValue">
    <span class="MPWSLabel">Current Page:</span>
    <span class="MPWSValue">1</span>
</div>

    <div class="MPWSWrapper">
        
        <div class="MPWSBlock MPWSBlockEdgeLinks">
            <a href="?plugin=toolbox&display=users&pg=1" class="MPWSLink MPWSLinkPaging">First</a>
            <a href="?plugin=toolbox&display=users&pg=2" class="MPWSLink MPWSLinkPaging">>></a>
            <a href="?plugin=toolbox&display=users&pg=3" class="MPWSLink MPWSLinkPaging">Last</a>
        </div>
        
        <div class="MPWSBlock MPWSBlockPageLinks">
                        <a href="?plugin=toolbox&display=users&pg=1" class="MPWSLink MPWSLinkPaging MPWSLinkPagingActive">1</a>
                                <a href="?plugin=toolbox&display=users&pg=2" class="MPWSLink MPWSLinkPaging">2</a>
                                <a href="?plugin=toolbox&display=users&pg=3" class="MPWSLink MPWSLinkPaging">3</a>
                </div>
    
    </div>
    
</div>

</div><?php }} ?>