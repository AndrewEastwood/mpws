define("default/js/view/accountLogin", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mView',
    /* template */
    'default/js/plugin/hbs!default/hbs/accountLogin'
], function ($, _, MView, tpl) {

    var View = MView.getNew();

    var AccountLogin = View.extend({
        viewName: 'AccountLoginPage',
        className: 'well',
        template: tpl
    });

    return AccountLogin;

});