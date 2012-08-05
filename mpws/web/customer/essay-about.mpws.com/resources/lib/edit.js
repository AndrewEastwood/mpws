(function (window, $, undefined) {

if (typeof(window.editable) === 'undefined') { window.editable = {} }
    
var editable = window.editable;

editable.goLiveEdit = function(){
    // get all static wrappers
    $('div.MPWSStaticContentWrapper').each(function(){
        $(this).hide();
        var editControl = $('<textarea>')
            .attr('name', $(this).attr('title'))
            .attr('id', 'MPWSEditBoxID')
            .text($(this).html());
        $(this).after(editControl);
    });
    
    // init nicEdit
    bkLib.onDomLoaded(function() {
        var myNicEditor = new nicEditor({iconsPath : '/static/nicEditorIcons.gif'});

        // set panel
        myNicEditor.setPanel('MPWSEditPanelID');

        // set editors
        myNicEditor.addInstance('MPWSEditBoxID');
    });
     
}

})(window, jQuery);
