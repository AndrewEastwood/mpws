<?php /*%%SmartyHeaderCode:171626206850804394541817-04942144%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b43d79ad5db39a467ed90062c78be6d51ad27400' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/widget/dataTableView.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '9a8ad909480ac298824cde057ccb3611e91b9ea5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/title.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '8353ed817f3a10aa18cb6a40909f78201e516c64' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/searchBox.html',
      1 => 1350663924,
      2 => 'file',
    ),
    'f9d26ffe8808e52db1aad649635991eda29ac324' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/header.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '497c121d4947324a67febd0f320f0a2ae4da1ea4' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/trigger/control.html',
      1 => 1350854759,
      2 => 'file',
    ),
    'dca5dd749b18945338a219bc2f54ca85de638484' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/htmlTextBox.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '1e3b7818f0fcb662e00f52afdfc4dc5773a1837f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/label.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '01ac54aedbbf6a7f49e46e1954b36f6473958844' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1350663924,
      2 => 'file',
    ),
    'cbb4b263bc8184a28cbef90069561b5b13fa096e' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1350853729,
      2 => 'file',
    ),
    '1f41ac3515949000dfb743e53eb00b074a6cc20f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1350853963,
      2 => 'file',
    ),
    '535697c6004dc371263dc35eb2c3e9633bf84300' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/simple/link.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '67ea9f35dff21e59593cdbd7ee49a730e06da33f' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataTable.html',
      1 => 1350854701,
      2 => 'file',
    ),
    '3f335be90f9375dee6ed29bf829b02878f82aa8b' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/pagingBar.html',
      1 => 1350663924,
      2 => 'file',
    ),
    '77e37e747b963f3d531054f4caa529e12b08f463' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/component/dataRow.html',
      1 => 1350663924,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '171626206850804394541817-04942144',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5084686a50dfa0_44848915',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5084686a50dfa0_44848915')) {function content_5084686a50dfa0_44848915($_smarty_tpl) {?>



<div id="MPWSWidgetDataTableViewSystemUsersID" class="MPWSWidget MPWSWidgetDataTableView MPWSWidgetDataTableViewSystemUsers">
    





<div class="MPWSComponent MPWSComponentTitle">
    <span class="MPWSText MPWSTextTitle">Active Users</span>
    <span class="MPWSText MPWSTextDetails">List of all active users</span>
    </div>

    <div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <div class="MPWSHeader">
    <h3>Searchbox</h3>
</div>
    <form action="" class="MPWSForm MPWSFormSearchBox" method="POST">
        <div class="MPWSFormBody">
        
                                                                        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            

<label for="MPWSControl0ID">
    <span class="MPWSText">User Name</span>
</label>
    

                    

    


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
    <div class="MPWSHeader">
    <h3>Quick Filtering</h3>
</div>
    
                                                
        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            

<label class="MPWSLabel">
    <span class="MPWSText">sort ASC</span>
</label>
    

            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=asc&amp;oid=" target="" class="MPWSLink" title="lower to higher" mpws-oid="" mpws-action="ASC">lower to higher</a>
        
    

</div>

    
                                                
        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            

<label class="MPWSLabel">
    <span class="MPWSText">sort ASC</span>
</label>
    

            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=asc&amp;oid=" target="" class="MPWSLink" title="lower to higher" mpws-oid="" mpws-action="ASC">lower to higher</a>
        
    

</div>

    </div>

    

<div id="MPWSComponentDataTableDataTableViewSystemUsersID" class="MPWSComponent MPWSComponentDataTable MPWSComponentDataTableDataTableViewSystemUsers">
    <div class="MPWSHeader">
    <h3>Matched Records</h3>
</div>





    <div class="MPWSBlock MPWSBlockTopActions">
    
                        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=new&amp;oid=" target="" class="MPWSLink" title="New Record" mpws-oid="" mpws-action="new">New Record</a>
        
    

</div>
                </div>


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
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=1" target="" class="MPWSLink" title="edit" mpws-oid="1" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=1" target="" class="MPWSLink" title="view" mpws-oid="1" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=1" target="" class="MPWSLink" title="delete" mpws-oid="1" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow1">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=2" target="" class="MPWSLink" title="edit" mpws-oid="2" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=2" target="" class="MPWSLink" title="view" mpws-oid="2" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=2" target="" class="MPWSLink" title="delete" mpws-oid="2" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">olololo</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">0000-00-00 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow2">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=3" target="" class="MPWSLink" title="edit" mpws-oid="3" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=3" target="" class="MPWSLink" title="view" mpws-oid="3" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=3" target="" class="MPWSLink" title="delete" mpws-oid="3" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">test3</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-10-21 23:47:19</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow3">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=4" target="" class="MPWSLink" title="edit" mpws-oid="4" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=4" target="" class="MPWSLink" title="view" mpws-oid="4" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=4" target="" class="MPWSLink" title="delete" mpws-oid="4" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">0</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow4">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=5" target="" class="MPWSLink" title="edit" mpws-oid="5" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=5" target="" class="MPWSLink" title="view" mpws-oid="5" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=5" target="" class="MPWSLink" title="delete" mpws-oid="5" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow5">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=6" target="" class="MPWSLink" title="edit" mpws-oid="6" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=6" target="" class="MPWSLink" title="view" mpws-oid="6" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=6" target="" class="MPWSLink" title="delete" mpws-oid="6" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow6">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=7" target="" class="MPWSLink" title="edit" mpws-oid="7" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=7" target="" class="MPWSLink" title="view" mpws-oid="7" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=7" target="" class="MPWSLink" title="delete" mpws-oid="7" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow7">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=8" target="" class="MPWSLink" title="edit" mpws-oid="8" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=8" target="" class="MPWSLink" title="view" mpws-oid="8" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=8" target="" class="MPWSLink" title="delete" mpws-oid="8" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow8">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=9" target="" class="MPWSLink" title="edit" mpws-oid="9" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=9" target="" class="MPWSLink" title="view" mpws-oid="9" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=9" target="" class="MPWSLink" title="delete" mpws-oid="9" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
            <div class="MPWSDataTableRow MPWSDataTableRow9">

                    <div class="MPWSDataTableCell MPWSDataTableCellActions">
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionEdit">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=edit&amp;oid=10" target="" class="MPWSLink" title="edit" mpws-oid="10" mpws-action="edit">edit</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionView">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=view&amp;oid=10" target="" class="MPWSLink" title="view" mpws-oid="10" mpws-action="view">view</a>
        
    

</div>
                </span>
                            <span class="MPWSDataTableCellAction MPWSDataTableCellActionDelete">
                
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">




            
        






    
            
    
                        
    
    
    
        
    
        



<a href="/page/tools.html?plugin=toolbox&amp;display=users&amp;action=delete&amp;oid=10" target="" class="MPWSLink" title="delete" mpws-oid="10" mpws-action="delete">delete</a>
        
    

</div>
                </span>
                        </div>
        
                    
                            <div class="MPWSDataTableCell MPWSDataTableCellName">TestUser</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellActive">1</div>
                            <div class="MPWSDataTableCell MPWSDataTableCellDateLastAccess">2012-06-26 00:00:00</div>
                            </div>
        
    </div>

</div>

    <div id="MPWSComponenPagingBarID" class="MPWSComponent MPWSComponenPagingBar">
    
    <div class="MPWSBlockDataRow">
    <span class="MPWSText">Filtered Records:</span>
    <span class="MPWSValue">24</span>
</div>
    
    <div class="MPWSBlockDataRow">
    <span class="MPWSText">Total Records:</span>
    <span class="MPWSValue">24</span>
</div>

    <div class="MPWSBlockDataRow">
    <span class="MPWSText">Table Always Shows:</span>
    <span class="MPWSValue">10</span>
</div>
    
    <div class="MPWSBlockDataRow">
    <span class="MPWSText">Total Pages:</span>
    <span class="MPWSValue">3</span>
</div>
    
    <div class="MPWSBlockDataRow">
    <span class="MPWSText">Current Page:</span>
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