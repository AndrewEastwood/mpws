<?php /*%%SmartyHeaderCode:429389730506c8f9c3da3e7-85699197%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b43d79ad5db39a467ed90062c78be6d51ad27400' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1349294202,
      2 => 'file',
    ),
    '67ea9f35dff21e59593cdbd7ee49a730e06da33f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html',
      1 => 1349297701,
      2 => 'file',
    ),
    '001f27e0f6123b935501ab627af0711ee9f6c81f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/objectSummary.html',
      1 => 1349295293,
      2 => 'file',
    ),
    '89315d90c92cff95f5c3b5148e051f8e4fa0af29' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/actionLink.html',
      1 => 1349298075,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '429389730506c8f9c3da3e7-85699197',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_506ca79ea9cb61_37437149',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_506ca79ea9cb61_37437149')) {function content_506ca79ea9cb61_37437149($_smarty_tpl) {?>





	<div class="MPWSComponent MPWSComponentDataTable">
    <div class="MPWSComponent MPWSComponentObjectSummary">
	<span class="MPWSText MPWSTextTitle">Active Users</span>
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
                    
	
<a href="?action=edit&amp;oid=XXX" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
	EDIT
</a>

                    </span>
                                    <span class="MPWSDataTableCellActionView">
                    
	
<a href="?action=view&amp;oid=XXX" class="MPWSLink" target="" mpws-action="view" title="VIEW">
	VIEW
</a>

                    </span>
                                    <span class="MPWSDataTableCellActionDelete">
                    
	
<a href="?action=delete&amp;oid=XXX" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
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
                    
	
<a href="?action=edit&amp;oid=XXX" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
	EDIT
</a>

                    </span>
                                    <span class="MPWSDataTableCellActionView">
                    
	
<a href="?action=view&amp;oid=XXX" class="MPWSLink" target="" mpws-action="view" title="VIEW">
	VIEW
</a>

                    </span>
                                    <span class="MPWSDataTableCellActionDelete">
                    
	
<a href="?action=delete&amp;oid=XXX" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
	REMOVE
</a>

                    </span>
                                </div>
            
            
                            <div class="MPWSDataTableCell MPWSDataTableCellName">olololo</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">0000-00-00 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow2">
                                                                        <div class="MPWSDataTableCell MPWSDataTableCellActions">
                                    <span class="MPWSDataTableCellActionEdit">
                    
	
<a href="?action=edit&amp;oid=XXX" class="MPWSLink" target="" mpws-action="edit" title="EDIT">
	EDIT
</a>

                    </span>
                                    <span class="MPWSDataTableCellActionView">
                    
	
<a href="?action=view&amp;oid=XXX" class="MPWSLink" target="" mpws-action="view" title="VIEW">
	VIEW
</a>

                    </span>
                                    <span class="MPWSDataTableCellActionDelete">
                    
	
<a href="?action=delete&amp;oid=XXX" class="MPWSLink" target="" mpws-action="delete" title="REMOVE">
	REMOVE
</a>

                    </span>
                                </div>
            
            
                            <div class="MPWSDataTableCell MPWSDataTableCellName">test3</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDatelastaccess">2012-10-02 21:15:01</div>
                            </div>
    
</div>

<?php }} ?>