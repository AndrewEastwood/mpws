define("plugin/account/site/js/view/menuSignUp", [
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/site/hbs/menuSignUp'
], function (MView, tpl) {

    var MenuSignUp = MView.extend({
        tagName: 'li',
        template: tpl
    });

    return MenuSignUp;

});