define("default/js/lib/extend.string", function () {

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
    // String.prototype.asCssClass = function() {
    //     return "." + this;
    // }
    String.prototype.startWith = function(str) {
        var _rez = this.match(new RegExp('^' + str));
        return !!(_rez && _rez.length);
    }
    String.prototype.endWith = function(str) {
        var _rez = this.match(new RegExp(str + '$'));
        return !!(_rez && _rez.length);
    }
    // String.prototype.asCssID = function() {
    //     return "#" + this;
    // }
    String.prototype.ucWords = function() {
        return this.replace(/\w\S*/g, function (txt) { return txt.capitalize(); });
    }
    String.prototype.compact = function () {
        return this.ucWords().replace(/[\s\\.]/gi, '');
    }
    String.prototype.has = function (needle) {
        return this.indexOf(needle) >= 0;
    }
    String.prototype.spaceless = function () {
        return this.replace(/(\s)+/gi, ' ').trim();
    }
    String.prototype.multistring = function (f) {
         return f.toString().split('\n').slice(1, -1).join('\n');
    }
    String.prototype.isUrl = function () {
        var regex = new RegExp("^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|www\\.){1}([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})+)(/(.)*)?(\\?(.)*)?");
         return regex.test(this);
    }
    String.prototype.hashCode = function() {
        var hash = 0, i, char;
        if (this.length === 0) return hash;
        for (i = 0, l = this.length; i < l; i++) {
            char  = this.charCodeAt(i);
            hash  = ((hash<<5)-hash)+char;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    }
    // String.prototype.jqElExistsAsID = function() {
    //     return $(this.asCssID()).length > 0;
    // }

});