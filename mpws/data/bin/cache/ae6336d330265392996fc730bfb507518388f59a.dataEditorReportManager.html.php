<?php /*%%SmartyHeaderCode:2074299187508a3ff60d4001-76363638%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ae6336d330265392996fc730bfb507518388f59a' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/widget/dataEditorReportManager.html',
      1 => 1351237818,
      2 => 'file',
    ),
    'a9e95e057f99621ee7164be23b6fedf8bc2d1fb7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/widget/baseDataEditor.html',
      1 => 1351257666,
      2 => 'file',
    ),
    'a0ddab4075dc8e4fdcb58fc91caa9a6edf3b90dc' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/component/title.html',
      1 => 1351057045,
      2 => 'file',
    ),
    '45d5c5a0e0df4a959ac5bb1504842e87b284cc0b' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/text.html',
      1 => 1351013530,
      2 => 'file',
    ),
    '331b902f5b3f9259cd18347d4cf8618679da3a53' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsFormButtons.html',
      1 => 1351014388,
      2 => 'file',
    ),
    'a291fbfdf58a8c4b968a9b89fa85536efefe03b7' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/trigger/control.html',
      1 => 1351061160,
      2 => 'file',
    ),
    'd02973ef8e80aa05e187fe40f5356d8e16a2e28e' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/control/mpwsLinkAction.html',
      1 => 1351057045,
      2 => 'file',
    ),
    'f53137f2b7fe9fb4a8d2f0e039ef190f3c30373d' => 
    array (
      0 => '/var/www/mpws/web/default/v1.0/template/simple/link.html',
      1 => 1350642297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2074299187508a3ff60d4001-76363638',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_508a9c51e9c8e4_74353338',
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508a9c51e9c8e4_74353338')) {function content_508a9c51e9c8e4_74353338($_smarty_tpl) {?>


<div id="MPWSWidgetdataEditorReportManagerID" class="MPWSWidget MPWSWidgetDataEditor MPWSWidgetdataEditorReportManager">
    
    
    
            




<div class="MPWSComponent MPWSComponentTitle">
    <span class="MPWSText MPWSTextTitle">Report Manager</span>
    <span class="MPWSText MPWSTextDetails">-</span>
        <span class="MPWSText MPWSTextCustom">Record Saved</span>
    </div>
        
    
        
        
    
    <form action="?action=new&plugin=reporting&display=allreports" name="data_edit_dataeditorreportmanager" method="POST" class="MPWSForm MPWSFormEditor MPWSFormEditorPageSave">
        <div class="MPWSFormBody">

    
    
            
        
        
        <span class="MPWSText">
    Saved    
</span>

        <span class="MPWSText">
    Use the buttons below:    
</span>

        
        
    
            </div>
    
        <div class="MPWSFormFooter">
    
    

<div class="MPWSControlField MPWSControlFieldFormButtons">
            <button id="MPWSControlFormButtonsNewID" type="submit" name="do" value="New" class="MPWSControl MPWSControlButtonNew">
            New
        </button>
    </div>
        </div>
    
    </form>

    
    <div class="MPWSBlock MPWSBlockDataEditorBottomLinks">
         
        
    
<div class="MPWSBlock MPWSBlockControl MPWSRenderModeNormal">


            
                        







<a href="/page/tools.html?plugin=reporting&display=allreports" target="" class="MPWSLink" title="Back To Records" Array>Back To Records</a>

    

</div>
    </div>

</div>

<?php }} ?>