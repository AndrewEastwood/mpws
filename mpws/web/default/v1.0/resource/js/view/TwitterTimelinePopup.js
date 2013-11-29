APP.Modules.register("view/TwitterTimelinePopup", [], [

    'lib/jquery',
    'widget/popup',
    'lib/htmlComponents',
    'widget/settings',
    'lib/jquery.zrssfeed'

], function (app, Sandbox, $, Popup, HtmlComponents, Settings) {

    var _libHtml = new HtmlComponents();
    var _config = app.Page.getConfiguration();

    var _labels = {
        popup_title: "Tweets by @",
        noData: 'There are no tweets'
    }

    var TwitterTimelinePopup = Popup.extend({
        initialize: function (options) {

            // adjust account name
            var accountScreenName = options.accountScreenName;
            if (typeof accountScreenName !== "string" || !accountScreenName || accountScreenName === '@')
                return;

            if (accountScreenName[0] === '@')
                accountScreenName = accountScreenName.substr(1);

            var self = this;

            // configure popup
            var _defaults = {
                showSpinner: true,
                title: _labels.popup_title + accountScreenName,
                dialogClass: 'twitter-timeline',
            };

            // init base view
            Popup.prototype.initialize.call(this, _.extend({}, _defaults));

            // render popup
            this.render();

            // get url to fetch timeline data
            var _twitterAccountTimelineUrl = Settings.getURL("tweets_timeline", {"00000": accountScreenName});

            // fetch data
            $.get(_twitterAccountTimelineUrl).done(function(data){
                // twiiter timeline wrapper
                var _wrapper = _libHtml.getWrapper('twitter-timeline');
                // varify data for required fields
                if(data && data.timeline && data.user) {
                    var _tweetList = _libHtml.generateTag('ol');
                    _(data.timeline).each(function(tweetEntry){
                        // create tweet entry
                        var _tweetEntry = _libHtml.generateTag('li');
                        var _tweetEntryHeader = _libHtml.getContainerBlock(false, '', 'tweet-header');
                        var _tweetEntryContent = _libHtml.getContainerBlock(false, '', 'tweet-content');

                        // add image
                        if (data.user.image)
                            _tweetEntryHeader.append(_libHtml.getImage(data.user.image));

                        // add account screen name
                        if (data.user.screen_name)
                            _tweetEntryHeader.append(_libHtml.getLabel(data.user.screen_name));

                        // set content
                        _tweetEntryContent.text($("<div/>").html(tweetEntry).text());

                        // update entry with header and content
                        _tweetEntry.append([_tweetEntryHeader, _tweetEntryContent]);

                        // add append entry to list of tweets
                        _tweetList.append(_tweetEntry);
                    });
                    // finally replace timeline contect with list of generated tweets 
                    _wrapper.html(_tweetList);
                }
                else if (data.error)
                    _wrapper.text(_libHtml.getLabelError(data.error));
                else
                    _wrapper.text(_labels.noData);

                self.$el.html(_wrapper);
            });

        }
    });

    return TwitterTimelinePopup;
});