/* Simple native bridge to 3dp template engine
 * --------
 */

APP.Modules.register("lib/templateEngine", [
    /* import globals */
    window

], [
    'lib/handlebars',
], function (wnd, app, Sandbox, tplEngine) {

	return tplEngine;

});