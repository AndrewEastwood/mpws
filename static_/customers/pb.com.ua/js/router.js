define([
    'jquery',
    'underscore',
    './view/breadcrumb'
], function ($, _) {

    var _customerOptions = {};

    _customerOptions.site = {
        title: APP.config.TITLE,
        logoImageUrl: APP.config.URL_PUBLIC_LOGO
    };

    // configure titles and brand images
    $('head title').text(_customerOptions.site.title);
    $('#site-logo-ID').attr({
        src: _customerOptions.site.logoImageUrl,
        title: _customerOptions.site.title,
        itemprop: 'logo'
    });
    $('.navbar-brand').removeClass('hide');
    // var breadcrumb = new Breadcrumb();
    // breadcrumb.render();

    // add banner image
    var $banner = $('<div>').addClass('banner-decor');
    $('.MPWSBannerHeaderTop').append($banner);

    function CustomerClass () {}

    CustomerClass.prototype.setBreadcrumb = function (options) {
        // breadcrumb.render(options);
        // APP.Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBreadcrumbTop',
        //     el: breadcrumb.$el.clone()
        // });
        // APP.Sandbox.eventNotify('global:content:render', {
        //     name: 'CommonBreadcrumbBottom',
        //     el: breadcrumb.$el.clone()
        // });
    }

    return CustomerClass;

});