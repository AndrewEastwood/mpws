// MPWS API
// --------

(function (window, document, $, mpws) {
    
    function _sendRequest (url, data, callback) {
        
        
        $.ajax({
            url: url,
            success: function(data) {
                
                var d = eval('('+data+')');
                //console.log(data);
                if (typeof(callback) === 'function')
                    callback(d);
            }
        });
    }
    
    function _pageRequest (fn, callback) {
        
        var _pageName = location.pathname.replace('.', '/').split('/').slice(-2, -1);
        
        if (!_pageName && !fn)
            return false;
        
        var _url = '/api/' + _pageName + '.js?fn=' + fn + '&p=' + encodeURIComponent('token=' + mpws.token || '');
        
        _sendRequest(_url, false, callback);
        return true;
    }
    
    function _objectRequest (sender, callback) {
        //mpws.tools.log('_objectRequest');
        // oid
        var _realm = $(sender).attr('mpws-realm');
        var _fn = $(sender).attr('mpws-action');
        var _oid = $(sender).attr('mpws-oid');
        var _custom = $(sender).attr('mpws-custom');

        if (typeof(_realm) === 'undefined' || typeof(_fn) === 'undefined') {
            mpws.tools.log('_objectRequest: empty realm or method name');
            return false;
        }
    
        var _params = 'name=' + $(sender).attr('name') || '';
        _params += '&id=' + $(sender).attr('id') || '';
        _params += '&value=' + $(sender).attr('value') || false;
        _params += '&checked=' + mpws.tools.empty($(sender).attr('checked'));
        _params += '&token=' + mpws.token || '';
        
        
        if (typeof(_oid) !== 'undefined')
            _params += '&oid=' + _oid;
        if (typeof(_custom) !== 'undefined')
            _params += '&' + _custom;
        

        var _url = '/api/' + _realm + '.js?fn=' + _fn + '&p=' + encodeURIComponent(_params);

        _sendRequest(_url, false, callback);
        return true;
    }
    
    mpws.api = {
        send: _sendRequest,
        objectRequest: _objectRequest,
        pageRequest: _pageRequest
    };
    
    
})(window, document, jQuery, (window.mpws = window.mpws || {})); 
    

