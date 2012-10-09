<?php /*%%SmartyHeaderCode:10284384755073417f827212-83124874%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b43d79ad5db39a467ed90062c78be6d51ad27400' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1349820870,
      2 => 'file',
    ),
    '001f27e0f6123b935501ab627af0711ee9f6c81f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html',
      1 => 1349814829,
      2 => 'file',
    ),
    '8353ed817f3a10aa18cb6a40909f78201e516c64' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/searchBox.html',
      1 => 1349813985,
      2 => 'file',
    ),
    'c99e47becdd382321c108454bf032312bfeb8885' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/simpleHeader.html',
      1 => 1349813369,
      2 => 'file',
    ),
    'cbb4b263bc8184a28cbef90069561b5b13fa096e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1349814810,
      2 => 'file',
    ),
    'd9294aed7ad6b1191011e1b5d20caadea91ec75e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTableTopActions.html',
      1 => 1349819471,
      2 => 'file',
    ),
    '89315d90c92cff95f5c3b5148e051f8e4fa0af29' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html',
      1 => 1349818760,
      2 => 'file',
    ),
    '67ea9f35dff21e59593cdbd7ee49a730e06da33f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html',
      1 => 1349818813,
      2 => 'file',
    ),
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1349809909,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10284384755073417f827212-83124874',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5074a3a6349775_29430073',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5074a3a6349775_29430073')) {function content_5074a3a6349775_29430073($_smarty_tpl) {?>



<div id="MPWSWidgetSystemUsersID" class="MPWSWidget MPWSWidgetSystemUsers">
    





<div class="MPWSComponent MPWSComponentObjectSummary">
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

    
<div id="MPWSComponenQuickFilteringID" class="MPWSComponent MPWSComponenQuickFiltering">
        <div class="MPWSComponentHeader">
    <h3>Quick Data Filtering</h3>
</div>
        <div class="MPWSBlock">
        <div class="MPWSDataRow">
            <label class="MPWSLabel">
                Show 
                User Names
                 from 
            </label>
            <span class="MPWSValue">
                                                                
                                                <a href="?plugin=toolbox&display=users&sort=Name.asc#MPWSComponenQuickFilteringID">
                    lower to higher
                </a>
            </span>
        </div>
    </div>
        <div class="MPWSBlock">
        <div class="MPWSDataRow">
            <label class="MPWSLabel">
                Show 
                Last Visit
                 from 
            </label>
            <span class="MPWSValue">
                                                                
                                                <a href="?plugin=toolbox&display=users&sort=DateLastAccess.asc#MPWSComponenQuickFilteringID">
                    lower to higher
                </a>
            </span>
        </div>
    </div>
    </div>

    

<div id="MPWSComponentDataTableTopActionsID" class="MPWSComponent MPWSComponentDataTableTopActions">



    
                        


	
	
	

	
			
	
	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=add" class="MPWSLink" title="Create New User" mpws-action="add">Create New User</a>


            
</div>
    

<div id="MPWSComponentDataTableID" class="MPWSComponent MPWSComponentDataTable">






    <div class="MPWSDataTableRows">
    
            <div class="MPWSDataTableRow MPWSDataTableRowCaptions">


        
                                    <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellName">
                    User Name
                </div>
                            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellIsonline">
                    Online State
                </div>
                            <div class="MPWSDataTableCell MPWSDataTableCellCaption MPWSDataTableCellDatelastaccess">
                    Last Time Visit
                </div>
                            </div>
    
            <div class="MPWSDataTableRow MPWSDataTableRow0">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=24" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=24" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=24" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow1">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=23" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=23" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=23" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow2">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=22" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=22" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=22" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow3">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=21" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=21" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=21" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow4">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=20" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=20" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=20" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow5">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=19" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=19" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=19" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow6">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=18" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=18" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=18" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow7">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=17" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=17" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=17" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow8">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=16" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=16" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=16" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow9">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellActionEdit">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=15" class="MPWSLink" title="EDIT" mpws-action="edit">EDIT</a>


                </span>
                            <span class="MPWSDataTableCellActionView">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=15" class="MPWSLink" title="VIEW" mpws-action="view">VIEW</a>


                </span>
                            <span class="MPWSDataTableCellActionDelete">
                


	
	
	

	
			
	
	            	
	
	        
        
                                
	
	
	<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=15" class="MPWSLink" title="REMOVE" mpws-action="delete">REMOVE</a>


                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellIsonline">0</div>
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