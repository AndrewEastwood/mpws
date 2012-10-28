var _r = null;

try{
    _r = (window.top.utils?window.top.pkgReport:null) || ((window.opener && window.opener.pkgReport)?window.opener.pkgReport:null)
}catch (err) { }

var pkgReport = _r || new function() {
    this.RenderReport = function (id) {
        this.beforeLoadHook('MPWSContentPlaceholderID');
        $.ajax({
            url: "/api/func=report&id="+id+"&format=html&action=show",
            data: "",
            context: this,
            success: function(data){
                this.afterLoadHook('MPWSContentPlaceholderID');
                var dataObj = eval('('+data+')');
                $('#MPWSContentPlaceholderID').html(dataObj.content);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this.afterLoadHook('MPWSContentPlaceholderID');
                $('#MPWSContentPlaceholderID').html(errorThrown);
            }
        });
    }
    this.beforeLoadHook = function (placeholderID) {
        $('#' + placeholderID).html('');
        $('#' + placeholderID).addClass('MPWSLoading');
    }
    this.afterLoadHook = function (placeholderID) {
        $('#' + placeholderID).removeClass('MPWSLoading');
    }
    this.updateUI = function () {
        $(window).scroll(function () {
            if ((window.scrollY + 75) > $('#MPWSContentPlaceholderID .MPWSWidgetContent').offset().top) {
                if(!$('#MPWSContentPlaceholderID .MPWSMiniBlockButtons').hasClass('MPWSRibbonFixed'))
                    $('#MPWSContentPlaceholderID .MPWSMiniBlockButtons').addClass('MPWSRibbonFixed');
            } else {
                if($('#MPWSContentPlaceholderID .MPWSMiniBlockButtons').hasClass('MPWSRibbonFixed'))
                    $('#MPWSContentPlaceholderID .MPWSMiniBlockButtons').removeClass('MPWSRibbonFixed');
            }
        });
    }

}
