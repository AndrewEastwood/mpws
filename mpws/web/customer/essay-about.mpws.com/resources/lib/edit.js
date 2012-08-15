(function (window, $, undefined) {

if (typeof(window.editable) === 'undefined') { window.editable = {} }
    
var editable = window.editable;

editable.goLiveEdit = function(){
    
    var _wwIds = [];
    
    // get all static wrappers
    $('div.MPWSStaticContentWrapper').each(function(){
        $(this).hide();
        
        // set id
        var _propId = $(this).attr('title');
        
        if (!!!_propId)
            return false;
        
        // normalize key
        _propId = mpws.page + '@' + _propId.toUpperCase().replace(/ /g, '_');

        // control id
        var _wwId = 'MPWSEditBox_' + mpws.tools.random() + 'ID';
        _wwIds.push(_wwId);
        
        var editControl = $('<textarea>')
            .attr('name', 'property@' + _propId)
            .attr('id', _wwId)
            .css({
                width: '100%',
                border: '2px solid #0e0'
            })
            .text($(this).html());
        $(this).after(editControl);
    });
    
    // init nicEdit
    bkLib.onDomLoaded(function() {
        var myNicEditor = new nicEditor({iconsPath : '/static/nicEditorIcons.gif', fullPanel : true});

        // set panel
        myNicEditor.setPanel('MPWSEditPanelID');

        // set editors
        for (var wwItem in _wwIds)
            myNicEditor.addInstance(_wwIds[wwItem]);
       // show top panel
       $('div.MPWSTopSlider').animate({marginTop: '-10px'}, 3000);

    });
     
}

})(window, jQuery);
