/* MPWS API
 * --------
 */

APP.Modules.register("lib/mpws.api", [
    /* import globals */
    window

], [
    'lib/jquery',
    'lib/underscore',
], function (wnd, app, Sandbox, $, _) {

    var apiService = '/api.js';

    app.log('lib/mpws.api ================> ', $, _);

    function _requestRaw (data, callback) {
        //app.log('trololo');
        app.log('requestRaw', data);
        return $.post(apiService, data).success(function(receivedData) {
            /*console.log(data);*/
            if (typeof(callback) === 'function')
                callback(receivedData);
        });
    }
    
    // send page request
    // call customer's API only
    function _pageRequest (fn, callback) {
        var _customerName = mpws.customer;
        if (!_customerName && !fn)
            return false;
        var _pglink = $('<a></a>').attr({
            'mpws-caller' : _customerName,
            'mpws-realm' : 'customer',
            'mpws-action' : fn
        });
        _requestRaw(_pglink, callback);
        return true;
    }
    
    // send request of provided object
    // mostly used to get plugin data
    function requestJSON (params, callback) {
        var requester = _getObjectJSON(params);
        app.log(requester);
        _requestRaw(requester, callback);
        return true;
    }
    
    // get sender request object 
    // using custom attributes
    function _getObjectJSON (params) {
        //mpws.tools.log('requestJSON');

        if (typeof(params.fn) === 'undefined') {
            app.log('requestJSON: caller or method name is empty');
            return false;
        }

        return {
            caller: params.caller || '*',
            fn: params.fn,
            p: $.extend(
            // default params
            {
                realm: "none"
            },
            // our custom params
            params.params,
            // finally put this to make sure we have all essential parameters
            {
                token: params.token || app.Page.getConfiguration().TOKEN
            })
        }
    }

    function _getObjectDOM (sender) {
        //mpws.tools.log('requestJSON');


        var _isDOM = $(sender).length > 0;

        var _caller = sender.caller || $(sender).attr('mpws-caller');
        var _realm = sender.realm || $(sender).attr('mpws-realm');
        var _fn = sender.fn || $(sender).attr('mpws-fn');

        if (typeof(_caller) === 'undefined' || typeof(_fn) === 'undefined') {
            app.log('requestJSON: caller or method name is empty');
            return false;
        }

        return {
            'caller': _caller || '*',
            'realm': _realm || 'none',
            'fn': _fn,
            'oid': sender.oid || $(sender).attr('mpws-oid'),
            'custom': sender.custom || $(sender).attr('mpws-custom'),
            'value': sender.value || $(sender).attr('value') || false,
            'name': sender.name || $(sender).attr('name') || '',
            'id': sender.id || $(sender).attr('id') || '',
            'checked': $.isEmpty($(sender).attr('checked')),
            getUrl: function() {
                var _params = 'name=' + this.name;
                _params += '&id=' + this.id;
                _params += '&value=' + this.value;
                _params += '&checked=' + this.checked;
                _params += '&token=' + sender.token || app.Page.getConfiguration().TOKEN;
                _params += '&realm=' + this.realm;
                if (sender.extendedParams)
                    _params += '&' + _(sender.extendedParams).map(function (v, k) { return k +'=' + v + '&'; });
                if (typeof(this.oid) !== 'undefined')
                    _params += '&oid=' + this.oid;
                if (typeof(this.custom) !== 'undefined')
                    _params += '&custom=' + this.custom;
                return apiService + '?caller='+this.caller+'&fn=' + this.fn + '&p=' + encodeURIComponent(_params);
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

    return {
        requestRaw: _requestRaw,
        request: requestJSON,
        objectRequest: requestJSON,
        pageRequest: _pageRequest,
        getObjectJSON: _getObjectJSON,
        getObjectJSONByKeyValue: _getObjectJSONByKeyValue
    };

});