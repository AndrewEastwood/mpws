/* MPWS API
 * --------
 */

(function (window, document, $, mpws) {


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

    function mpwsLiveEditor () {
        
        var _settings = {
            elements: 'div.MPWSStaticContentWrapper',
            propNameAttr: 'title',
            editBar: {},
            editor: false
        };
        
        function _showEditBar () {
            if (typeof(editor) === 'undefined')
                return false;
            
            // wrap all elements
            $('body').wrapInner('<form method="post" action="" id="MPWSFormLiveEditorID">');
            
            // set top panel
            $('#MPWSFormLiveEditorID').prepend(' \
                <div class="MPWSTopSlider" style="margin-top:-100px;"> \
                    <img src="/static/mpws-log_mini_eee.png"> \
                    <div id="MPWSEditPanelID"></div> \
                    <div class="MPWSButtonBlock"> \
                        <input type="submit" name="do" id="MPWSFormLiveEditorButtonSaveID" value="Save Changes"> \
                        <input type="submit" name="do" id="MPWSFormLiveEditorButtonExitID" value="Exit Editor"> \
                    </div> \
                </div>');
            
        }

        this.setup = function (settings) {
            $.extend(true, _settings, settings);
            return this;
        };
        
        this.closeEdit = function () {
            $('#MPWSFormLiveEditorButtonExitID').click();
            return this;
        }
        
        this.doEdit = function (callback) {

            var _wwIds = [];
            
            // remove all forms
            $('form').remove();
            
            //
            if (!_showEditBar())
                return false;
            
            
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

            // send textarea IDs to callback
            if (typeof(callback) === 'function')
                callback(_wwIds);

           // show top panel
           $('div.MPWSTopSlider').animate({marginTop: '-10px'}, 3000);
       
            return this;
        };

    }
    
    mpws.ui = {
        tooltip: mpwsTooltip
    }

})(window, document, jQuery, (window.mpws = window.mpws || {})); 
