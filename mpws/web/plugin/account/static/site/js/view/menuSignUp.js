define("plugin/account/js/view/site/menuSignUp", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/site/menuSignUp'
], function (MView, tpl) {

    var MenuSignUp = MView.extend({
        tagName: 'li',
        template: tpl
    });

    return MenuSignUp;

});