/* MPWS API
 * --------
 */
(function (window, document, $, mpws) {
    
    function _sendRequest (url, data, callback, useEval) {
        //mpws.tools.log('trololo');
        $.ajax({
            url: url,
            success: function(result) {
                if (useEval)
                    result = eval('('+result+')');
                /*console.log(data);*/
                if (typeof(callback) === 'function')
                    callback(result);
            }
        });
    }
    
    // send page request
    // call customer's API only
    function _pageRequest (fn, callback, useEval) {
        var _customerName = mpws.customer;
        if (!_customerName && !fn)
            return false;
        var _pglink = $('<a></a>').attr({
            'mpws-caller' : _customerName,
            'mpws-realm' : 'customer',
            'mpws-action' : fn
        });
        _sendRequest(_pglink, callback, useEval);
        return true;
    }
    
    // send request of provided object
    // mostly used to get plugin data
    function _objectRequest (sender, callback, useEval) {
        var requester = _getObjectJSON(sender);
        //mpws.tools.log(requester.getUrl());
        _sendRequest(requester.getUrl(), false, callback, useEval);
        return true;
    }
    
    // get sender request object 
    // using custom attributes
    function _getObjectJSON (sender) {
        //mpws.tools.log('_objectRequest');
        var _caller = $(sender).attr('mpws-caller');
        var _realm = $(sender).attr('mpws-realm');
        var _fn = $(sender).attr('mpws-fn');

        if (typeof(_caller) === 'undefined' || typeof(_fn) === 'undefined') {
            mpws.tools.log('_objectRequest: caller or method name is empty');
            return false;
        }

        return {
            'caller': _caller || '*',
            'realm': _realm || 'none',
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
                _params += '&realm=' + this.realm;
                if (typeof(this.oid) !== 'undefined')
                    _params += '&oid=' + this.oid;
                if (typeof(this.custom) !== 'undefined')
                    _params += '&custom=' + this.custom;
                return '/api.js?caller='+this.caller+'&fn=' + this.fn + '&p=' + encodeURIComponent(_params);
            }
        }
    }

    function _getObjectJSONByKeyValue (kv) {
        var _l = $('<a></a>');
        var _custom = '';
        // caller
        if (kv.caller)
            _l.attr('mpws-caller', kv.caller);
        // action
        if (kv.fn)
            _l.attr('mpws-fn', kv.fn);
        // realm
        if (kv.realm)
            _l.attr('mpws-realm', kv.realm);
        // oid
        if (kv.oid)
            _l.attr('mpws-oid', kv.oid);
        if (kv.custom) {
            for (var cidx in kv.custom)
                _custom += cidx + "=" + kv.custom[cidx] + '&';
            _l.attr('mpws-custom', encodeURIComponent(_custom));
        }
        
        return _getObjectJSON(_l);
    }

    mpws.api = {
        send: _sendRequest,
        objectRequest: _objectRequest,
        pageRequest: _pageRequest,
        getObjectJSON: _getObjectJSON,
        getObjectJSONByKeyValue: _getObjectJSONByKeyValue
    };
    
    
})(window, document, jQuery, (window.mpws = window.mpws || {})); 
    

