var _s = null;

try{
    _s = (window.top.utils?window.top.pkgSurvey:null) || ((window.opener && window.opener.pkgSurvey)?window.opener.pkgSurvey:null)
}catch (err) { }

var pkgSurvey = _s || new function() {
    this.loadForm = function (id) {
        this.beforeLoadHook('MPWSContentPlaceholderID');
        $.ajax({
            url: "/api/func=poll&id="+id+"&format=html&action=show",
            data: "",
            context: this,
            success: function(data){
                this.afterLoadHook('MPWSContentPlaceholderID');
                var dataObj = eval('('+data+')');
                $('#MPWSContentPlaceholderID').html(dataObj.content);
                //$('.MPWSGroup:gt(1)').accordion({ animated: 'fast', collapsible: true});
                //$(".MPWSGroupTitle").click();
                //render_wizard('MPWSWidgetPollID .MPWSWidgetContent', ['MPWSMiniBlockPollID', 'MPWSMiniBlockGroupsID']);
                this.updateUI();
                /*
                switch (dataObj.status.toLowerCase())
                {
                    case 'preview': break;
                    case 'required': break;
                    case 'saved': break;
                }*/
                /*var callIndex = 1000;
                for (var initFn in ratingInit) {
                    setTimeout(ratingInit[initFn], callIndex);
                    callIndex = callIndex + 100;
                    console.log(callIndex);
                }*/
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this.afterLoadHook('MPWSContentPlaceholderID');
                $('#MPWSContentPlaceholderID').html(errorThrown);
            }
        });
    }

    this.editForm = function (sender) {
        var polldata = $('input[name="edit-data"]').val();
        this.beforeLoadHook('MPWSContentPlaceholderID');
        $.ajax({
            url: "/api/func=poll&id="+$(this).attr("mpws-pollid")+"&format=html&action=edit",
            data: "polldata=" + polldata,
            type: "POST",
            context: this,
            success: function(data){
                this.afterLoadHook('MPWSContentPlaceholderID');
                //$('#MPWSContentPlaceholderID').html(data);
                var dataObj = eval('('+data+')');
                $('#MPWSContentPlaceholderID').html(dataObj.content);
                //$('.MPWSGroup:gt(1)').accordion({ animated: 'fast', collapsible: true});
                //$(".MPWSGroupTitle").click();
                //render_wizard('MPWSWidgetPollID .MPWSWidgetContent', ['MPWSMiniBlockPollID', 'MPWSMiniBlockGroupsID']);
                this.updateUI();
                /*
                switch (dataObj.status.toLowerCase())
                {
                    case 'preview': break;
                    case 'required': break;
                    case 'saved': break;
                }*/
                /*
                var callIndex = 1000;
                for (var initFn in ratingInit) {
                    setTimeout(ratingInit[initFn], callIndex);
                    callIndex = callIndex + 100;
                    console.log(callIndex);
                }*/
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this.afterLoadHook('MPWSContentPlaceholderID');
                $('#MPWSContentPlaceholderID').html(errorThrown);
            }
        });
    }

    this.previewForm = function (sender) {
        var polldata = collectPollData(this);
        this.beforeLoadHook('MPWSContentPlaceholderID');
        $.ajax({
            url: "/api/func=poll&id=" + $(this).attr("mpws-pollid") + "&action=preview&format=html",
            data: "polldata=" + encodeURIComponent(polldata),
            type: "POST",
            context: this,
            success: function(data){
                this.afterLoadHook('MPWSContentPlaceholderID');
                var dataObj = eval('('+data+')');
                $('#MPWSContentPlaceholderID').html(dataObj.content);
                this.updateUI();
                console.log(dataObj.status);
                switch (dataObj.status.toLowerCase())
                {
                    case 'preview': break;
                    case 'required': break;
                    case 'saved': break;
                    case 'uncompleted': {
                        $('.MPWSGroup:gt(1)').accordion({ animated: 'fast', collapsible: true});
                        
                        $(".MPWSGroup:not(:has(.MPWSError)) .MPWSGroupTitle").click();
                        //$(".MPWSGroup:not(:has(.MPWSError)) .MPWSGroupTitle").append(' - completed');
                        //$(".MPWSGroup:not(:has(.MPWSError)) .MPWSGroupTitle").addClass('MPWSCompleted');
                        
                        /*$('.MPWSGroup:gt(1)').map(function(){
                            if ($(this).has("div.MPWSError"))
                                $($($(this).contents().filter('h3'))[0]).click();
                        });*/
                        //$(".MPWSGroupTitle").click();
                        //$('.MPWSGroup:gt(1):has(".MPWSError") .MPWSGroupTitle').click();
                        break;
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this.afterLoadHook('MPWSContentPlaceholderID');
                $('#MPWSContentPlaceholderID').html(errorThrown);
            }
        });
    }

    this.postForm = function (sender) {
        //var polldata = false;
        /*if (fromEditPage)
            polldata = encodeURIComponent(collectPollData(this));
        else*/
        var polldata = $('input[name="edit-data"]').val();
        this.beforeLoadHook('MPWSContentPlaceholderID');
        $.ajax({
            url: "/api/func=poll&id=" + $(this).attr("mpws-pollid") + "&action=submit",
            data: "polldata=" + polldata,
            type: "POST",
            context: this,
            success: function(data){
                this.afterLoadHook('MPWSContentPlaceholderID');
                var dataObj = eval('('+data+')');
                $('#MPWSContentPlaceholderID').html(dataObj.content);
                this.updateUI();
                /*
                switch (dataObj.status.toLowerCase())
                {
                    case 'preview': break;
                    case 'required': break;
                    case 'saved': break;
                }*/
            },
            error: function (jqXHR, textStatus, errorThrown) {
                this.afterLoadHook('MPWSContentPlaceholderID');
                $('#MPWSContentPlaceholderID').html(errorThrown);
            }
        });
    }

    this.collectPollData = function (sender) {
        //window.console.log('collectPollData');
        //window.console.log(sender);
        //window.console.log($(sender).attr("mpws-pollid"));
        //window.console.log($(sender).attr("mpws-poll-version"));
        //window.console.log('#MPWSWidgetPoll'+$(sender).attr("mpws-pollid")+'ID .MPWSWidgetContent [mpws-input]');
        //var pollDataArray = $('.MPWSWidgetPoll .MPWSWidgetContent [mpws-input]').map(function(){
        var pollDataArray = $('#MPWSWidgetPoll'+$(sender).attr("mpws-pollid")+'ID .MPWSWidgetContent [mpws-input]').map(function(){
            return '"' + $(this).attr('name') + '":"' + $(this).val() + '"';
        });
        //window.console.log(pollDataArray.toArray().join(','));
        var polldata = '{' + 
            '"id":"' + $(sender).attr("mpws-pollid") + '",' + 
            '"version":"' + $(sender).attr("mpws-poll-version") + '",' + 
            '"Answers":{' + pollDataArray.toArray().join(',') + '}' + 
        '}';
        
        //window.console.log($('.MPWSWidgetPoll .MPWSWidgetContent [mpws-input]').length);
        //window.console.log(polldata);
        return polldata;
    }

    this.beforeLoadHook = function (placeholderID) {
        $('#' + placeholderID).html('');
        $('#' + placeholderID).addClass('MPWSLoading');
    }

    this.afterLoadHook = function (placeholderID) {
        $('#' + placeholderID).removeClass('MPWSLoading');
    }

    this.closeForm = function () {
        $('#MPWSContentPlaceholderID').html('');
    }

    this.updateUI = function () {
        //console.log('update ui');
        if ($('#MPWSCButtonPreviewID'))
            $('#MPWSCButtonPreviewID').click(this.previewForm);
        if ($('#MPWSCButtonEditID'))
            $('#MPWSCButtonEditID').click(this.editForm);
        if ($('#MPWSCButtonSubmitID'))
            $('#MPWSCButtonSubmitID').click(this.postForm);
        if ($('#MPWSCButtonCancelID'))
            $('#MPWSCButtonCancelID').click(this.closeForm);
        //$('.MPWSButton').button({icons:{primary:"ui-icon-check"}});
        window.scrollTo(0,0);

        $(window).scroll(function () {
            //console.log((window.scrollY + 75) + ' > ' + $('#MPWSContentPlaceholderID .MPWSMiniBlockButtons').position().top);
            //console.log((window.scrollY + 75) + ' > ' + $('#MPWSContentPlaceholderID .MPWSWidgetContent').offset().top);
            
            // if buttons are visible
            //var btnBlock = $('#MPWSContentPlaceholderID .MPWSMiniBlockButtons:not([class$="MPWSRibbonFixed"])');
            //var wgtContainer = ;
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
