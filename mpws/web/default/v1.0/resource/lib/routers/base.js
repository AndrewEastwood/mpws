// it is the base page router

qB.Modules.register("router/base", [
    /* import globals */
    window

], [

    /* import dep packages */
    'lib/jquery',
    'lib/jquery_ui',
    'message',
    'common',

    /* component implementation */
], function(wnd, qB, Sandbox, $) {

    // predefined basic deps
    var modulePageMap = [
        /* ecqtasks */
        {
            match: ["/enterprise/ecqtasks/$"],
            deps: ["router/page.ecqtasks"]
        },
        /* page company */
        {
            match: ["/enterprise/create/$"],
            deps: ["router/page.company.create"]
        },
        {
            match: ["/enterprise/edit/$"],
            deps: ["router/page.company.edit"]
        },
        /* page users */
        {
            match: ["/enterprise/users/$", "/enterprise/group/.*/users/"],
            deps: ["router/page.users.list"]
        },
        {
            match: ["/enterprise/users/create/managed/$"],
            deps: ["router/page.users.managed_create"],
            stop: true
        },
        {
            match: ["/enterprise/users/create/$"],
            deps: ["router/page.users.create"]
        },
        {
            match: ["/enterprise/user/.*/edit/$"],
            deps: ["router/page.users.edit"]
        },
        {
            match: ["/enterprise/user/.*/status/$"],
            deps: ["router/page.users.status"]
        },
        {
            match: ["/enterprise/user/.*/reports/$"],
            deps: ["router/page.users.reports"]
        },
        {
            match: ["/enterprise/user/.*/reset_password/$"],
            deps: ["router/page.users.reset_password"]
        },
        /* page roles */
        {
            match: ["/enterprise/groups/$"],
            deps: ["router/page.groups.list"]
        },
        {
            match: ["/enterprise/group/create/$"],
            deps: ["router/page.groups.create"]
        },
        {
            match: ["/enterprise/group/.*/edit/$"],
            deps: ["router/page.groups.edit"]
        },
        {
            match: ["/enterprise/group/.*/status/$"],
            deps: ["router/page.groups.status"]
        },
        {
            match: ["/enterprise/group/.*/reports/$"],
            deps: ["router/page.groups.reports"]
        },
        {
            match: ["/enterprise/group/.*/users/$"],
            deps: ["router/page.groups.users"]
        },
        /* page global settings */
        {
            match: ["/enterprise/global_settings/$"],
            deps: ["router/page.global.settings"]
        },
        /* page misc */
        {
            match: ["/enterprise/misc/$"],
            deps: ["router/page.misc"]
        },
        {
            match: ["/enterprise/misc/endpointreport/$"],
            deps: ["router/page.misc.endpointreport"]
        },
        /* page tweets */
        {
            match: ["/enterprise/tweets/$"],
            deps: ["router/page.tweets.list"]
        },
        {
            match: ["/enterprise/tweets/create/$"],
            deps: ["router/page.tweets.create"]
        },
        /* page rsses */
        {
            match: ["/enterprise/rsses/$"],
            deps: ["router/page.rsses.list"]
        },
        {
            match: ["/enterprise/rsses/create/$"],
            deps: ["router/page.rsses.create"]
        },
        /* page publications */
        {
            match: ["/enterprise/publications/$"],
            deps: ["router/page.publications.list"]
        },
        {
            match: ["/enterprise/publications/create/$"],
            deps: ["router/page.publications.create"]
        },
        {
            match: ["/enterprise/publications/.*/edit/$"],
            deps: ["router/page.publications.edit"]
        },
        /* page budget */
        {
            match: ["/enterprise/budget/"],
            deps: ["router/page.budget"]
        },
        {
            match: ["/enterprise/budget/stories/$"],
            deps: ["router/page.budget.stories"]
        },
        {
            match: ["/enterprise/budget/usage/$"],
            deps: ["router/page.budget.usage"]
        }
    ];

    var BasePageRouter = function () {};

    BasePageRouter.getPageModuels = function () {
        var activePage = qB.Page.getUrl();
        
        qB.log(true, 'active page is ', activePage);

        var modulesToDownload = [];
        var entry = null;
        var _stopped = false;

        for (var id in modulePageMap) {
            entry = modulePageMap[id];
            for (var ptrId in entry.match)
                if (activePage.match(entry.match[ptrId])) {
                    for (var i = 0, len = entry.deps.length; i < len; i++)
                        if (modulesToDownload.indexOf(entry.deps[i]) < 0)
                            modulesToDownload.push(entry.deps[i]);
                    if (entry.stop)
                        _stopped = true;
                }
            if (_stopped)
                break;
        }

        return modulesToDownload;
    }

    BasePageRouter.start = function () {

        qB.log(true, 'Base page router started');

        // here we get page modules
        var pageModules = this.getPageModuels();

        qB.log(true, 'loading: ', pageModules);

        // include page dependencies
        qB.Modules.downloadPackages(pageModules, function () {
            // notify all subscribers that page is ready
            $(document).ready(function () {
                Sandbox.eventNotify("page-loaded");
                qB.log(true ,'page loaded');
            });
        });

    };

    BasePageRouter.onPageLoaded = function (ctx, callback) {
        Sandbox.eventSubscribe("page-loaded", function () {
            if (typeof callback === "function")
                callback.apply(ctx);
        });
    }

    return BasePageRouter;

});