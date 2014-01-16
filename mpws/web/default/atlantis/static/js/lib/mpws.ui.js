/* MPWS UI
 * --------
 */
define("default/js/lib/mpws.ui", [
    'cmn_jquery',
    'default/js/lib/jquery_ui',
    'default/js/lib/lightbox',
    'default/js/lib/bootstrap',
    'default/js/lib/bootstrap-combobox',
    'default/js/lib/bootstrap-magnify',
    /* component implementation */
], function (jQuery, _) {

});


/*(function (window, document, $, mpws) {


    function mpwsTooltip (text, binder, prefix, removeAfter) {
        
        var _text = text || ' ';
        var _binder = binder;
        var _prefix = prefix || 'Default';
        var _removeAfter = removeAfter || -1;
        var _self = this;
        var _onclick;
        var _animateIn = 1500;
        var _animateOut = 500;
        
        
        function _getTooltip () {
            if (!_text || !_binder)
                return {};
            
            var $pos = $('#'+_binder).offset();
            var $tip = $('<div></div>')
                .addClass('MPWSJSTooltip')
                .addClass('MPWSJSTooltip_' + _prefix)
                .css({
                    'position': 'absolute',
                    'left': $pos.left,
                    'top': $pos.top - 100,
                    'opacity': 0
                })
                .append($('<div></div>').addClass('MPWSTooltipTopSeparator'))
                .append(
                    $('<span></span>')
                        .addClass('MPWSTooltipBody')
                        .text(_text)
                )
                .append($('<div></div>').addClass('MPWSTooltipBottomSeparator'));
                
            return {
                object: $tip,
                target: $pos
            };
        };

        this.setup = function (settings) {
            _text = settings.text || _text;
            _binder = settings.binder || _binder;
            _prefix = settings.prefix || _prefix;
            _removeAfter = settings.removeAfter || _removeAfter;
            _onclick = settings.onclick || _onclick;
            _animateIn = settings.animateIn || _animateIn;
            _animateOut = settings.animateOut || _animateOut;
            
            return this;
        };
        
        this.getSettings = function () {
            return {
                text: _text,
                binder: _binder,
                prefix: _prefix,
                removeAfter: _removeAfter
            };
        }

        this.clear = function (selector, callback) {
            var cbRez = false;
            //mpws.tools.log('clear');
            var $items = $(selector);
            if ($items.length)
                $items.animate(
                    {opacity:0,'margin-top':'100px'},
                    _animateOut,
                    function(){
                        $(this).remove();
                        if (!!callback)
                            cbRez = callback();
                    }
                );
            else
                if (!!callback)
                    cbRez = callback();
    
            return cbRez;

        };

        this.clearAll = function (callback) {
            //mpws.tools.log('clearAll');
            var cbRez = this.clear('.MPWSJSTooltip', callback);
            if (!!callback)
                return cbRez;
        };
        
        
        this.showModal = function (callback) {
            //mpws.tools.log('-----------2');
            //mpws.tools.log(this.clearAll);
            //mpws.tools.log('-----------2');
            //mpws.tools.log('showModal');
            var cbRez = this.clearAll(this.show);
            if (!!callback)
                cbRez = callback();
        };
        
        this.show = function (callback) {
            //mpws.tools.log('show');
            var _id = 'MPWSJSTooltip_' + mpws.tools.random() + 'ID';
            var _tooltip = _getTooltip();
            
            _tooltip.object.attr('id', _id);
            //mpws.tools.log('-----------1');
            //mpws.tools.log(_self.clearAll);
            //mpws.tools.log('-----------1');
            $('body').append(_tooltip.object);

            if (_removeAfter > 0) {
                //mpws.tools.log('-----------will be removed after ' + _removeAfter);
                _tooltip.object.animate({opacity:1,top:_tooltip.target.top}, _animateIn, function (){
                    setInterval(function(){_self.clear('#' + _id);},_removeAfter)
                });
            } else
                _tooltip.object.animate({opacity:1,top:_tooltip.target.top}, _animateIn);
            if (!!callback)
                callback(_id);
        };
 
    };

    function mpwsLiveEditor (settings) {

        var _settings = {
            elements: 'div.MPWSStaticContentWrapper',
            propertyNameSource: 'title',
            editBar: {
                setup: "opacity:0;",
                animation: {
                    fx: {marginTop:'-10px',opacity:0.95},
                    time: 1500
                }
            },
            editor: false,
            isActive: $.cookie("MPWS_LIVE_EDIT_MODE") === 'OK',
            isAutomatic: true,
            callback : false,
            customElementsToRemove: false
        };
        
        function _showEditBar () {
            //mpws.tools.log('_showEditBar');
            if (typeof(_settings.editor) === 'undefined')
                return false;

            // remove all forms
            $('form').remove();

            // wrap all elements
            $('body').wrapInner('<form method="post" action="" id="MPWSFormLiveEditorID">');

            // remove vustom elements
            if (_settings.customElementsToRemove)
                $(_settings.customElementsToRemove).remove();

            // set top panel
            $('#MPWSFormLiveEditorID').prepend(' \
                <div class="MPWSTopSlider" style="margin-top:-40px;'+_settings.editBar.setup+'"> \
                    <img src="/static/mpws-log_mini_eee.png"> \
                    <div id="MPWSEditPanelID"></div> \
                    <div class="MPWSButtonBlock"> \
                        <input type="submit" name="do" id="MPWSFormLiveEditorButtonSaveID" value="Save Changes"> \
                        <input type="submit" name="do" id="MPWSFormLiveEditorButtonExitID" value="Exit Editor"> \
                    </div> \
                </div>');
            
            return true;
        };

        this.setup = function (settings) {
            //mpws.tools.log('setup');
            if (!!settings.isActive)
                settings.isActive = $.cookie("MPWS_LIVE_EDIT_MODE") === 'OK';
            $.extend(true, _settings, settings);

            //mpws.tools.log(_settings);

            if (_settings.isAutomatic && _settings.isActive)
                this.doEdit();

            return this;
        };
        
        this.closeEdit = function () {
            //mpws.tools.log('closeEdit');
            $('#MPWSFormLiveEditorButtonExitID').click();
            return this;
        }
        
        this.doEdit = function () {
            //mpws.tools.log('doEdit');
            //mpws.tools.log(_settings);

            // exit if inactive
            if (!_settings.isActive)
                return false;
            //mpws.tools.log('doEdit: is active');

            // setup top edit bar
            if (!_showEditBar())
                return false;
            //mpws.tools.log('doEdit: edit bar is installed');

            // our edit boxes
            var _wwIds = [];

            // get all static wrappers
            $(_settings.elements).each(function(){
                $(this).hide();
                
                // set id
                var _propId = $(this).attr(_settings.propertyNameSource);
                
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

            // send textarea IDs to callback
            if (typeof(_settings.callback) === 'function')
                _settings.callback(_wwIds, _settings);

           // show/animate top panel
           $('div.MPWSTopSlider').animate(_settings.editBar.animation.fx, _settings.editBar.animation.time);
       
            return this;
        };

        if (typeof(settings) !== 'undefined')
            this.setup(settings);
    }
    
    function mpwsFileUpload (settings) {
        var _settings = {
            injectTo: 'div#MPWSFileUploadSection',
            isAutomatic: true,
            callback: false,
            maxUploads: 5,
            files: [],
            realm: 'default',
            fileInputName: 'mpws_files',
            properties: {
                link_add: '+1 source',
                link_remove: 'remove'
            },
            showUploadsOnly: false
        };
        
        var _self = this;

        var _uploadCounter = 0;

        function _html () {
            var _h = {
                buttons: {},
                links: {
                    // append one more input file
                    add: $('<a>')
                        .attr({
                            type: 'button',
                            href: 'javascript://'
                        })
                        .addClass('MPWSLinkAddFile')
                        .text(_settings.properties.link_add),
                    // remove linked input file
                    remove: $('<a>')
                        .attr({
                            type: 'button',
                            href: 'javascript://'
                        })
                        .addClass('MPWSLinkRemoveile')
                        .text(_settings.properties.link_remove),
                    // delete linked input file
                    cancel: $('<a>')
                        .attr({
                            type: 'button',
                            href: 'javascript://'
                        })
                        .addClass('MPWSLinkCancel')
                        .text(_settings.properties.link_remove)
                },
                inputs: {
                    file: $('<input>')
                        .attr({
                            type: 'file',
                            name: _settings.fileInputName + '[]'
                        })
                        .addClass('MPWSControlInput MPWSControlInputFileUpload'),
                    fileUploadKey: $('<input>')
                        .attr({
                            type: 'hidden',
                            name: 'fileUploadKey'
                        }),
                    fileCleanup: $('<input>')
                        .attr({
                            type: 'hidden',
                            name: 'fileCleanup',
                            id: 'MPWSControlInputFileUploadCleanup'
                        })
                },
                sections: {
                    controlField: $('<div>')
                        .attr({
                            id: 'MPWSSectionControlField'
                        }),
                    fileContainer: $('<div>')
                        .attr({
                            id: 'MPWSSectionFileUploadContainer'
                        }),
                    fileUploadedContainer: $('<div>')
                        .attr({
                            id: 'MPWSSectionFileUploadedContainer'
                        }),
                    fileField: $('<div>')
                        .addClass('MPWSSectionFileUploadField')
                }
            };
            return _h;
        };
        
        this.setup = function (settings) {
            //mpws.tools.log('setup');
            if (typeof(settings) !== 'undefined')
                $.extend(true, _settings, settings);
            //mpws.tools.log(_settings);
            if (_settings.isAutomatic)
                this.doInject();
            return this;
        };
        
        function _getLiveObj (htmlObj) {
            return $('#' + htmlObj.attr('id'));
        };
        
        function _getHtmlObj(htmlObj) {
            return htmlObj.clone();
        }
        
        function _getFUKey () {
            return 'mpws_fu_' + (_settings.realm || 'default');
        }
        
        this.doInject = function () {
            //mpws.tools.log('doInject');
            //mpws.tools.log(_html);
            
            // get inject element
            var _$injectElem = $(_settings.injectTo);
            
            if (_$injectElem.length == 0)
                return false;
            
            // add control section
            
            var _controls = _html().sections.controlField;
            var _fileContainer = _html().sections.fileContainer;
            var _fileUploadedContainer = _html().sections.fileUploadedContainer;
            
            // add uploaded files
            var _uploadedFileCount = mpws.tools.getObjectCount(_settings.files);
            if (_uploadedFileCount) {
                _uploadCounter = _uploadedFileCount;
                //mpws.tools.log(_settings.files.length);
                for (fileIndex in _settings.files) {
                    var _ufFld = _getHtmlObj(_html().sections.fileField);
                    
                    // add remove link
                    if (!_settings.showUploadsOnly) {
                        _ufFld.append(_getHtmlObj(_html().links.cancel).click(function(){

                            //mpws.tools.log('cancel upload file: ' + $(this).parent().attr('mpws-file'));
                            // get existed files to remove
                            var _rmfiles = $('#MPWSControlInputFileUploadCleanup').val();
                            $('#MPWSControlInputFileUploadCleanup').val(_rmfiles + $(this).parent().attr('mpws-file') + ';');
                            // remove file row
                            $(this).parent().remove();
                            // increase upload counter
                            _uploadCounter--;
                            // make add link visible
                            if (_add.css('display') == 'none')
                                _add.css({'display':''});
                            
                        }));
                    }
                    
                    // add uploaded item
                    if ($.isNumeric(fileIndex))
                        _ufFld.append('<span>' +_settings.files[fileIndex]+ '</span>');
                    else
                        _ufFld.append('<span><a href="'+fileIndex+'" target="blank">' +_settings.files[fileIndex]+ '</a></span>');
                    // add file info
                    _ufFld.attr('mpws-file', _settings.files[fileIndex]);
                    
                    _fileUploadedContainer.append(_ufFld);
                }
                    
            }
            
            //mpws.tools.log(_settings.files);
            
            // add link
            if (!_settings.showUploadsOnly) {
                var _add = _html().links.add;
                _add.click(function(){
                    //mpws.tools.log('_add.click');
                    if (_uploadCounter > _settings.maxUploads - 1) {
                        $(this).hide();
                        return;
                    }
                    var _fFld = _getHtmlObj(_html().sections.fileField);
                    var _remove = _getHtmlObj(_html().links.remove);
                    var _file = _getHtmlObj(_html().inputs.file);

                    // init file realm
                    _file.attr('name',_getFUKey() + '[]');

                    _remove.click(function(){
                        //mpws.tools.log('_remove.click');
                        $(this).parent().remove();
                        _uploadCounter--;
                        if (_add.css('display') == 'none')
                            _add.css({'display':''});
                    });
                    _fFld.append(_file);
                    _fFld.append(_remove);
                    _fFld.attr('id', 'MPWSFileField_' + mpws.tools.random() + '_ID');
                    //mpws.tools.log(_fFld);
                    //_getLiveObj(_html().sections.fileContainer).prepend(_fFld);
                    $('#MPWSSectionFileUploadContainer').append(_fFld);

                    _uploadCounter++;

                    if (_uploadCounter > _settings.maxUploads - 1)
                        $(this).hide();
                });
                
                if (_uploadCounter > _settings.maxUploads - 1)
                    _add.hide();
                
                _controls.append(_add);
            }
            

            // add controls
            _controls.append(_html().inputs.fileUploadKey.val(_getFUKey()));
            _controls.append(_html().inputs.fileCleanup);
            
            // combine sections
            var _$fileUploader = $('<div>')
                .attr('id', 'MPWSWidgetFileUpload')
                .append(_fileContainer)
                .append(_fileUploadedContainer)
                .append(_controls);
                
            // add view mode
            if (_settings.showUploadsOnly)
                _$fileUploader.addClass('MPWSViewStateUploadsOnly');
            else
                _$fileUploader.addClass('MPWSViewStateNormal');
            
            // inject file uploader
            _$injectElem.append(_$fileUploader);
        }
        
        this.setup(settings);
    }
    
    function mpwsFloatingBox (selector, topPadding) {
        if (!$(selector).length)
            return;
        var offset = $(selector).offset();
        var topPadding = topPadding || 15;
        $(window).scroll(function() {
            if ($(window).scrollTop() > offset.top) {
                $(selector).stop().animate({
                    marginTop: $(window).scrollTop() - offset.top + topPadding
                });
            } else {
                $(selector).stop().animate({
                    marginTop: 0
                });
            };
        });
    };
    
    function mpwsBoxAnimation_1 (selector) {
        $(selector).hover(
            function(){
                var ownerId = $(this).attr('id');
                // wrapper
                $('#' + ownerId + ' .MPWSWrapper').stop(true, false).animate({height: '50px'}, 300);
                // info line
                $('#' + ownerId + ' .MPWSWrapper .MPWSTextDescription').stop(true, false).animate({marginLeft: '0px'}, 500);
                // start line
                $('#' + ownerId + ' .MPWSWrapper .MPWSTextLink').stop(true, false).animate({marginRight: '0px'}, 500);
            },
            function(){
                var ownerId = $(this).attr('id');
                // wrapper
                $('#' + ownerId + ' .MPWSWrapper').stop(true, false).animate({height: '20px'}, 500);
                // info line
                $('#' + ownerId + ' .MPWSWrapper .MPWSTextDescription').stop(true, false).animate({marginLeft: '-300px'}, 400);
                // start line
                $('#' + ownerId + ' .MPWSWrapper .MPWSTextLink').stop(true, false).animate({marginRight: '-300px'}, 400);
            }
        );
    }
    
    mpws.ui = {
        tooltip: mpwsTooltip,
        liveEditor: mpwsLiveEditor,
        fileUpload: mpwsFileUpload,
        floatingBox: mpwsFloatingBox,
        animBox1: mpwsBoxAnimation_1
    }

})(window, document, jQuery, (window.mpws = window.mpws || {})); 
*/