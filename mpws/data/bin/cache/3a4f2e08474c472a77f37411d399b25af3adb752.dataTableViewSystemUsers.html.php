<?php /*%%SmartyHeaderCode:2930318505086910e18c3f8-41111766%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a4f2e08474c472a77f37411d399b25af3adb752' => 
    array (
      0 => '/var/www/mpws/web/plugin/toolbox/template/widget/dataTableViewSystemUsers.html',
      1 => 1351013544,
      2 => 'file',
    ),
    'cfba05c74377b2c69bee7235dc67ef05f8f2ffb5' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/baseDataTableView.html',
      1 => 1351014707,
      2 => 'file',
    ),
    'a0ddab4075dc8e4fdcb58fc91caa9a6edf3b90dc' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/title.html',
      1 => 1351013580,
      2 => 'file',
    ),
    '11949475bf39a92dd41d7b3b130e15a87110c9cd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/searchBox.html',
      1 => 1351014602,
      2 => 'file',
    ),
    'a9e48bbe01dbc0e9e1875b6afe94b60600cbec79' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/header.html',
      1 => 1350642619,
      2 => 'file',
    ),
    'a291fbfdf58a8c4b968a9b89fa85536efefe03b7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/trigger/control.html',
      1 => 1351015797,
      2 => 'file',
    ),
    '2827081c62f53487a9d79a04da7f97cdee4c78bf' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/htmlTextBox.html',
      1 => 1350992404,
      2 => 'file',
    ),
    '532b187efab104354fd4473020382d17605c376b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/label.html',
      1 => 1351015595,
      2 => 'file',
    ),
    '331b902f5b3f9259cd18347d4cf8618679da3a53' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1351014388,
      2 => 'file',
    ),
    '1a0890e2fef5427fab806f0d3f45ac8d91d053dd' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/quickFiltering.html',
      1 => 1351016140,
      2 => 'file',
    ),
    'd02973ef8e80aa05e187fe40f5356d8e16a2e28e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1351015448,
      2 => 'file',
    ),
    'f53137f2b7fe9fb4a8d2f0e039ef190f3c30373d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/link.html',
      1 => 1350642297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2930318505086910e18c3f8-41111766',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5086dedd4a9fe6_39290295',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5086dedd4a9fe6_39290295')) {function content_5086dedd4a9fe6_39290295($_smarty_tpl) {?>





<div id="MPWSWidgetDataTableViewSystemUsersID" class="MPWSWidget MPWSWidgetDataTableView MPWSWidgetDataTableViewSystemUsers">
    





<div class="MPWSComponent MPWSComponentTitle">
    <span class="MPWSText MPWSTextTitle">Active Users</span>
    <span class="MPWSText MPWSTextDetails">List of all active users</span>
    </div>

    <div id="MPWSComponentSearchBoxID" class="MPWSComponent MPWSComponentSearchBox">
    <div class="MPWSHeader">
    <h3>Simple Search Box</h3>
</div>
    <form action="" class="MPWSForm MPWSFormSearchBox" method="POST">
        <div class="MPWSFormBody">
        
                                                                        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            

<label for="MPWSControlTextBoxNameID">
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
    <h3>Quick Data Filtering</h3>
</div>
    
                                                
        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            

<label class="MPWSLabel">
    <span class="MPWSText">User Name</span>
</label>
                
                        







<a href="?plugin=toolbox&display=users&sort=Name.asc#MPWSComponenQuickFilteringID" target="" class="MPWSLink" title="lower to higher" Array>lower to higher</a>

    

</div>

    
                                                
        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            

<label class="MPWSLabel">
    <span class="MPWSText">Last Access:</span>
</label>
                
                        







<a href="?plugin=toolbox&display=users&sort=DateLastAccess.asc#MPWSComponenQuickFilteringID" target="" class="MPWSLink" title="lower to higher" Array>lower to higher</a>

    

</div>

    </div>



</div><?php }} ?>