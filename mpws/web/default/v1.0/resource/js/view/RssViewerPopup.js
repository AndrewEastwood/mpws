APP.Modules.register("view/RssViewerPopup", [], [

    'lib/jquery',
    'widget/popup',
    'lib/htmlComponents',
    'lib/jquery.zrssfeed'

], function (APP, Sandbox, $, Popup, HtmlComponents) {

    var _libHtml = new HtmlComponents();
    var _config = APP.Page.getConfiguration();

    var RssViewerPopup = Popup.extend({
        initialize: function (options) {
            var self = this;

            // configure popup
            var _defaults = {
                showSpinner: true,
                title: 'RSS: ' + options.title,
                dialogClass: 'rss-viewer',
            };

            // init base view
            Popup.prototype.initialize.call(this, _.extend({}, _defaults));

            // rss wrapper
            var _wrapper = _libHtml.getWrapper('rss-embedded');

            // render popup
            this.render();

            // load rss
            $(_wrapper).rssfeed(options.rss, {
                header: false,
                limit: 100
            });
        }
    });

    return RssViewerPopup;
});