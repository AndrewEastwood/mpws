define([
    "underscore",
    "string"
], function(_) {

    var _events = {};

    window.sandbox = _events;

    // Sandbox
    var _Sandbox = {
        // subscribe on eventID
        eventSubscribe: function (eventID, listener) {
            if (!_events[eventID])
                _events[eventID] = [];
            _events[eventID].push(listener);
        },
        eventUnsubscribe: function (eventID, listener) {
            if (!_events[eventID])
                return;
            _events[eventID] = _(_events[eventID]).without(listener);
        },
        _eventSubscribe : function (eventID, listener) {
            if (!_events[eventID])
                _events[eventID] = [];

            var listenerHash = listener && listener.toString().hashCode();
            var alreadyAdded = false;

            // avoid duplicates
            for (var i = 0, len = _events[eventID].length; i < len && !alreadyAdded; i++) {
                alreadyAdded = (_events[eventID][i].id === listenerHash);
                // override function
                if (alreadyAdded)
                    _events[eventID][i].fn = listener;
            }

            // add another listener
            if (!alreadyAdded) {
                // _app.log(true, 'adding subscriber on ', eventID);
                _events[eventID].push({
                    id : listenerHash,
                    fn : listener
                });
                // return listenerHash;
            } else 
                ;//_app.log(true, 'this subscriber is already added on ', eventID);
            return listenerHash;
        },
        // remove callback subscription to eventID 
        _eventUnsubscribe : function (eventID, listener) {

            // maybe eventId is hash, so let's remove all listeners by hash
            if (eventID)
                for (var eventGroupKey in _events)
                    for (var eventItemKey in _events[eventGroupKey])
                        if (_events[eventGroupKey][eventItemKey].id === eventID)
                            _events[eventGroupKey][eventItemKey].splice(eventItemKey, 1);

            if (!_events[eventID])
                return false;
            var listenerHash = listener && listener.toString().hashCode();
            var unsubcribeCount = 0;
            for (var i = 0, len = _events[eventID].length; i < len; i++)
                if (_events[eventID][i].id === listenerHash) {
                    _events[eventID].splice(i, 1);
                    unsubcribeCount++;
                }
            return unsubcribeCount > 0;
        },
        eventNotify : function (eventID, data, callback) {
            var listeners = _events[eventID];
            if (!listeners)
                return;
            var results = [];
            var rez = null;
            // _app.log(true, 'eventNotify: >>>>>>>>>>>>> ', eventID, ' >>>>>>>>> ');
            // _app.log(true, listeners);
            // _app.log(true, 'eventNotify: <<<<<<<<<<<<< ', eventID, ' <<<<<<<<< ');
            // loop through listeners
            for (var i = 0, len = _events[eventID].length; i < len; i++)
                results.push(listeners[i](data));
            // adjust result
            // if (results.length == 1)
            //     rez = results.pop();
            // else
            //     rez = results;
            // _app.log(true, 'eventNotify: has result', rez, results);
            // perform callback with results
            if (typeof callback === "function")
                callback(null, results);
            return rez;
        }
    };

    return _Sandbox;

});