define("customer/js/router", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'customer/js/view/breadcrumb',
    'default/js/plugin/css!customer/css/theme.css'
], function (Sandbox, $, _, Breadcrumb) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: APP.config.URL_PUBLIC_TITLE,
        logoImageUrl: APP.config.URL_PUBLIC_HOMEPAGE + APP.config.URL_STATIC_CUSTOMER + 'img/logo.png'
    };

    // configure titles and brand images
    $('head title').text(_customerOptions.site.title);
    $('#site-logo-ID').attr({
        src: _customerOptions.site.logoImageUrl,
        title: _customerOptions.site.title,
        itemprop: 'logo'
    });
    $('.navbar-brand').removeClass('hide');
    var breadcrumb = new Breadcrumb();
    // breadcrumb.render();

    // add banner image
    var $banner = $('<div>').addClass('banner');
    $('.MPWSBannerHeaderTop').append($banner);

    function CustomerClass () {}

    CustomerClass.prototype.setBreadcrumb = function (options) {
        breadcrumb.render(options);
        Sandbox.eventNotify('global:content:render', {
            name: 'CommonBreadcrumbTop',
            el: breadcrumb.$el.clone()
        });
        Sandbox.eventNotify('global:content:render', {
            name: 'CommonBreadcrumbBottom',
            el: breadcrumb.$el.clone()
        });
    }

    return CustomerClass;

});