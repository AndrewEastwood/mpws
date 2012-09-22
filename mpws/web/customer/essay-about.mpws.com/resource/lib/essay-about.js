//alert('customer');
mpws.module.define('essay-about', (function(window, document, $){

    $(document).ready(function () {

        /* all pages */
        //get team-lead name by schedule
        $('div.MPWSOnlineName span').text(mpws.tools.getDailyString(['Joshua', 'Daniel', 'Ethan', 'Caleb', 'Emily', 'Mia', 'Madison']));
        // social
        $('div.MPWSBlockSocial').sharrre({
            share: {
                googlePlus: true,
                facebook: true,
                twitter: true,
                pinterest: true,
                delicious: true
            },
            buttons: {
                googlePlus: {size: 'standard',annotation: 'none'},
                facebook: {layout: 'standart'},
                twitter: {count: 'none', via: 'essay-about.com'},
                delicious: {size: 'normal'},
                pinterest: {description: $('meta[name="description"]').attr('content'), layout: 'none'}
            },
            enableTracking: true,
            urlCurl: "/service/public/sharrre.php",
            enableCounter: false
        });
        // make it floating
        mpws.ui.floatingBox('div.MPWSBlockSocial');
        //review
        if ($('#MPWSLinkWriterReviewID').fancybox)
            $('#MPWSLinkWriterReviewID').fancybox({
                maxWidth    : 800,
                maxHeight   : 600,
                fitToView   : false,
                width       : '70%',
                height      : '70%'
            });
        

        /* make an order page */
        if (mpws.page === "make-order") {
            // upload banner
            var _tooltip = new mpws.ui.tooltip();
            _tooltip.setup({
                binder: 'MPWSFormHintSourceBannerID',
                prefix: 'SourceTipBanner',
                animateIn: 1,
                animateOut: 1
            });
            $('a.MPWSSourceTipBanner').hover(
                function(){
                    _tooltip.setup({
                        prefix: 'SourceTipBanner_' + mpws.tools.parseUrl($(this).attr('href')).host
                    }).showModal();
                },
                function(){
                    _tooltip.clearAll();
                }
            );
        }
        /* account page */
        if (mpws.page === "account") {
            // login availability
            $('#MPWSButtonCheckLoginID').click(function(){
                var requester = mpws.api.getObjectJSON(this);
                requester.value = $('#MPWSControlTextUserLoginRegisterID').val();
                //mpws.tools.log(requester);
                //mpws.tools.log(requester.getUrl());
                mpws.api.send(requester.getUrl(), false, mpwsLoginStateReceived)
                // MPWSControlTextUserLoginRegisterID
            });
            // changes message status
            $('.MPWSControl_MessageMarkAsRead').click(function(){
                mpws.api.objectRequest(this);
            });
            // changes writer's online status
            $('#MPWSControl_SetOnlineStatusID').click(function(){
                mpws.api.objectRequest(this);
            });
        }
        /* common pages */
        if (mpws.page === "make-order" || mpws.page === 'account') {
            // date pickers
            $('.MPWSControlOrderDateRange').datepicker({
                    showOn: "button",
                    buttonImage: "/static/icons/calendar.png",
                    dateFormat: "yy-mm-dd 00:00:00 TT",
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    ampm: true,
            });
            // make order datetime picker
            $('#MPWSControlMakeOrderDateDeadlineID').datetimepicker({
                    showOn: "button",
                    ampm: true,
                    buttonImage: "/static/icons/calendar.png",
                    dateFormat: "yy-mm-dd",
                    timeFormat: "hh:00:00 TT",
                    timezone: '-6000',
                    changeMonth: true,
                    changeYear: true,
                    showMinute: false,
                    showButtonPanel: true//,
                    //minDate: new Date(new Date().getTime() + (3*60*60*1000))
            });
            // resources
            $('#MPWSOrderSourceCountID').change(function(){
                // get current sources count
                var _currentSrcs = $('#MPWSSectionOrderSourcesLinksID input[type="text"]');
                var _newCount = $(this).val();
                var _field = '<div class="MPWSSourceRow"><input type="text" size="45" name="order_source_links[]" value=""/></div>';
                var _cDiff = _newCount - _currentSrcs.length;
                
                //console.log(_newCount);
                if (_currentSrcs.length < _newCount) {
                    var _newFields = '';
                    for(var i = 0; i < _cDiff ; i++)
                        _newFields += _field;
                    $('#MPWSSectionOrderSourcesLinksID').append(_newFields);
                } else {
                    if (_newCount == 0)
                        $('#MPWSSectionOrderSourcesLinksID').html('<div class="MPWSSourceRow"></div>');
                    else
                        $($('#MPWSSectionOrderSourcesLinksID .MPWSSourceRow:gt('+ _newCount +')')).remove();
                }
                
                // show helper text
                mpws.tools.log(_newCount);
                if(_newCount > 0)
                    $('#MPWSFormHintSourceBannerID').removeClass('MPWSHidden');
                else
                    $('#MPWSFormHintSourceBannerID').addClass('MPWSHidden');
                
            });
            // add +1 order source
            $('#MPWSOrderAddMoreSourcesID').click(function(){
                var _field = '<div class="MPWSSourceRow"><input type="text" size="45" name="order_source_links[]" value=""/><a href="#_remove" id="MPWSOrderRemoveSourcesID">remove</a></div>';
                $('#MPWSSectionOrderSourcesLinksID').append(_field);
            });
            // remove order source
            $('#MPWSOrderRemoveSourcesID').live('click', function(){
                $(this).parent().remove();
            });
        }

        /* non account pages */
        if (mpws.page !== 'account') {
            // live edit component
            new mpws.ui.liveEditor({
                editor: (!!window.nicEditor) ? new nicEditor({iconsPath:'/static/nicEditorIcons.gif',fullPanel:true}):false,
                customElementsToRemove: "div#top_menu_wrapper",
                callback: function (wwids, settings) {
                    // setup edit panel
                    settings.editor.setPanel('MPWSEditPanelID');
                    // setup edit boxes
                    for (var wwItem in wwids)
                        settings.editor.addInstance(wwids[wwItem]);
                }
            });
        }

    });

    function mpwsLoginStateReceived (data) {
        var _tooltip = new mpws.ui.tooltip();
        _tooltip.setup({
            binder: 'MPWSControlTextUserLoginRegisterID',
            prefix: 'UserDoubleLogin',
            removeAfter: 5000
        });

        //mpws.tools.log(_tooltip.getSettings());
        
        if (data && data.login == '')
            _tooltip.setup({text:'Login is empty.'}).showModal();
        else {
            if (data && !data.isAvailable)
                _tooltip.setup({text:'Login is already used.'}).showModal();
            else
                _tooltip.setup({text:'Available.'}).showModal();
        }
        //mpws.tools.log(data);
    }
    
    return { };
})(window, document, jQuery));
