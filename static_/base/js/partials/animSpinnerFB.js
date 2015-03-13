define(['handlebars', 'text!base/hbs/animationFacebook.hbs'], function (Handlebars, tpl) {
    Handlebars.registerPartial('animSpinnerFB', tpl);
    return Handlebars.compile(tpl);
});