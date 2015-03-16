define(['jquery', 'backbone'], function ($, Backbone) {
    return function (title, backUrl) {
        var $wrapper = $('<span/>');
            $backBtn = $('<span/>').addClass('fa fa-fw fa-arrow-circle-left'),
            $backLink = null,
            $label = $('<span/>').text(title);

        if (backUrl) {
            $backLink = $('<a/>').attr('href', 'javascript://').addClass('plugin-system-user-window-backlink');
            $(document.body).on('click', '.plugin-system-user-window-backlink', function () {
                Backbone.history.navigate(backUrl, true);
            });
            $wrapper.append($backLink.html($backBtn));
        }

        $wrapper.append($label);
        return $wrapper;
    };
})