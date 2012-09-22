/* MPWS API
 * --------
 */
(function (window, document, $, mpws) {
    
    function _sendRequest (url, data, callback) {

        $.ajax({
            url: url,
            success: function(data) {
                
                var d = eval('('+data+')');
                /*console.log(data);*/
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
        var requester = _getObjectJSON(sender);
        _sendRequest(requester.getUrl(), false, callback);
        return true;
    }
    
    function _getObjectJSON (sender) {
        /*mpws.tools.log('_objectRequest');*/
        var _realm = $(sender).attr('mpws-realm');
        var _fn = $(sender).attr('mpws-action');

        if (typeof(_realm) === 'undefined' || typeof(_fn) === 'undefined') {
            mpws.tools.log('_objectRequest: empty realm or method name');
            return false;
        }
    
        return {
            'realm': _realm,
            'fn': _fn,
            'oid': $(sender).attr('mpws-oid'),
            'custom': $(sender).attr('mpws-custom'),
            'value': $(sender).attr('value') || false,
            'name': $(sender).attr('name') || '',
            'id': $(sender).attr('id') || '',
            'checked': mpws.tools.empty($(sender).attr('checked')),
            getUrl: function() {
                var _params = 'name=' + this.name;
                _params += '&id=' + this.id;
                _params += '&value=' + this.value;
                _params += '&checked=' + this.checked;
                _params += '&token=' + mpws.token || '';
                if (typeof(this.oid) !== 'undefined')
                    _params += '&oid=' + this.oid;
                if (typeof(this.custom) !== 'undefined')
                    _params += '&' + this.custom;
                return '/api/' + this.realm + '.js?fn=' + this.fn + '&p=' + encodeURIComponent(_params);
            }
        }
    }
    
    
    mpws.api = {
        send: _sendRequest,
        objectRequest: _objectRequest,
        pageRequest: _pageRequest,
        getObjectJSON: _getObjectJSON
    };
    
    
})(window, document, jQuery, (window.mpws = window.mpws || {})); 
    

