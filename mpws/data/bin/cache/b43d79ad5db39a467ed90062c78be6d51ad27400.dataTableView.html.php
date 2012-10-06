<?php /*%%SmartyHeaderCode:429389730506c8f9c3da3e7-85699197%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b43d79ad5db39a467ed90062c78be6d51ad27400' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1349561277,
      2 => 'file',
    ),
    '8353ed817f3a10aa18cb6a40909f78201e516c64' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/searchBox.html',
      1 => 1349557253,
      2 => 'file',
    ),
    'cbb4b263bc8184a28cbef90069561b5b13fa096e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1349557286,
      2 => 'file',
    ),
    '67ea9f35dff21e59593cdbd7ee49a730e06da33f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html',
      1 => 1349379816,
      2 => 'file',
    ),
    '001f27e0f6123b935501ab627af0711ee9f6c81f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html',
      1 => 1349381648,
      2 => 'file',
    ),
    '89315d90c92cff95f5c3b5148e051f8e4fa0af29' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html',
      1 => 1349379795,
      2 => 'file',
    ),
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1349562191,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '429389730506c8f9c3da3e7-85699197',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5070af929a9e39_84964383',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5070af929a9e39_84964383')) {function content_5070af929a9e39_84964383($_smarty_tpl) {?>



    SEARCH BOX

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
    
    <div class="MPWSComponentSummary">
        <div class="MPWSComponentSummaryRow">
            <label>Filtered Records:</label>
            <span class="MPWSValue">24</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Total Records:</label>
            <span class="MPWSValue">24</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Table Always Shows:</label>
            <span class="MPWSValue">10</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Total Pages:</label>
            <span class="MPWSValue">3</span>
        </div>
        <div class="MPWSComponentSummaryRow">
            <label>Current Page:</label>
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
<?php }} ?>