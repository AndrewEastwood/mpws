Ext.data.JsonP.Backgrid_Extension_ClientSideFilter({
  "tagname": "class",
  "name": "Backgrid.Extension.ClientSideFilter",
  "extends": "Backgrid.Extension.ServerSideFilter",
  "mixins": [

  ],
  "alternateClassNames": [

  ],
  "aliases": {
  },
  "singleton": false,
  "requires": [

  ],
  "uses": [

  ],
  "enum": null,
  "override": null,
  "inheritable": null,
  "inheritdoc": null,
  "meta": {
  },
  "private": null,
  "id": "class-Backgrid.Extension.ClientSideFilter",
  "members": {
    "cfg": [

    ],
    "property": [
      {
        "name": "className",
        "tagname": "property",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "property-className"
      },
      {
        "name": "events",
        "tagname": "property",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "property-events"
      },
      {
        "name": "fields",
        "tagname": "property",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "property-fields"
      },
      {
        "name": "name",
        "tagname": "property",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "property-name"
      },
      {
        "name": "placeholder",
        "tagname": "property",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "property-placeholder"
      },
      {
        "name": "tagName",
        "tagname": "property",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "property-tagName"
      },
      {
        "name": "template",
        "tagname": "property",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "property-template"
      },
      {
        "name": "wait",
        "tagname": "property",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "property-wait"
      }
    ],
    "method": [
      {
        "name": "clear",
        "tagname": "method",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "method-clear"
      },
      {
        "name": "clearButton",
        "tagname": "method",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "method-clearButton"
      },
      {
        "name": "initialize",
        "tagname": "method",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "method-initialize"
      },
      {
        "name": "makeMatcher",
        "tagname": "method",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "method-makeMatcher"
      },
      {
        "name": "makeRegExp",
        "tagname": "method",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "method-makeRegExp"
      },
      {
        "name": "render",
        "tagname": "method",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
          "chainable": true
        },
        "id": "method-render"
      },
      {
        "name": "search",
        "tagname": "method",
        "owner": "Backgrid.Extension.ClientSideFilter",
        "meta": {
        },
        "id": "method-search"
      },
      {
        "name": "searchBox",
        "tagname": "method",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "method-searchBox"
      },
      {
        "name": "showClearButtonMaybe",
        "tagname": "method",
        "owner": "Backgrid.Extension.ServerSideFilter",
        "meta": {
        },
        "id": "method-showClearButtonMaybe"
      }
    ],
    "event": [

    ],
    "css_var": [

    ],
    "css_mixin": [

    ]
  },
  "linenr": 176,
  "files": [
    {
      "filename": "backgrid-filter.js",
      "href": null
    }
  ],
  "html_meta": {
  },
  "statics": {
    "cfg": [

    ],
    "property": [

    ],
    "method": [

    ],
    "event": [

    ],
    "css_var": [

    ],
    "css_mixin": [

    ]
  },
  "component": false,
  "superclasses": [
    "Backgrid.Extension.ServerSideFilter"
  ],
  "subclasses": [
    "Backgrid.Extension.LunrFilter"
  ],
  "mixedInto": [

  ],
  "parentMixins": [

  ],
  "html": "<div><pre class=\"hierarchy\"><h4>Hierarchy</h4><div class='subclass first-child'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='docClass'>Backgrid.Extension.ServerSideFilter</a><div class='subclass '><strong>Backgrid.Extension.ClientSideFilter</strong></div></div><h4>Subclasses</h4><div class='dependency'><a href='#!/api/Backgrid.Extension.LunrFilter' rel='Backgrid.Extension.LunrFilter' class='docClass'>Backgrid.Extension.LunrFilter</a></div></pre><div class='doc-contents'><p>ClientSideFilter is a search form widget that searches a collection for\nmodel matches against a query on the client side. The exact matching\nalgorithm can be overriden by subclasses.</p>\n</div><div class='members'><div class='members-section'><div class='definedBy'>Defined By</div><h3 class='members-title icon-property'>Properties</h3><div class='subsection'><div id='property-className' class='member first-child inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-property-className' class='name expandable'>className</a><span> : String</span></div><div class='description'><div class='short'> ...</div><div class='long'>\n<p>Defaults to: <code>&quot;backgrid-filter form-search&quot;</code></p></div></div></div><div id='property-events' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-property-events' class='name not-expandable'>events</a><span> : Object</span></div><div class='description'><div class='short'>\n</div><div class='long'>\n<p>Overrides: <a href='#!/api/Backgrid.Extension.ServerSideFilter-property-events' rel='Backgrid.Extension.ServerSideFilter-property-events' class='docClass'>Backgrid.Extension.ServerSideFilter.events</a></p></div></div></div><div id='property-fields' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-property-fields' class='name expandable'>fields</a><span> : ?Array.&lt;string&gt;</span></div><div class='description'><div class='short'>A list of model field names to\nsearch for matches. ...</div><div class='long'><p>A list of model field names to\nsearch for matches. If null, all of the fields will be searched.</p>\n</div></div></div><div id='property-name' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-property-name' class='name expandable'>name</a><span> : string</span></div><div class='description'><div class='short'>Query key ...</div><div class='long'><p>Query key</p>\n<p>Defaults to: <code>&#39;q&#39;</code></p></div></div></div><div id='property-placeholder' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-property-placeholder' class='name not-expandable'>placeholder</a><span> : string</span></div><div class='description'><div class='short'><p>The HTML5 placeholder to appear beneath\nthe search box.</p>\n</div><div class='long'><p>The HTML5 placeholder to appear beneath\nthe search box.</p>\n</div></div></div><div id='property-tagName' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-property-tagName' class='name expandable'>tagName</a><span> : String</span></div><div class='description'><div class='short'> ...</div><div class='long'>\n<p>Defaults to: <code>&quot;form&quot;</code></p></div></div></div><div id='property-template' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-property-template' class='name not-expandable'>template</a><span> : function(Object, ?Object=): string</span></div><div class='description'><div class='short'>\n</div><div class='long'>\n</div></div></div><div id='property-wait' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-property-wait' class='name expandable'>wait</a><span> : Number</span></div><div class='description'><div class='short'>The time in milliseconds to wait since the last\nchange to the search box's value before searching. ...</div><div class='long'><p>The time in milliseconds to wait since the last\nchange to the search box's value before searching. This value can be\nadjusted depending on how often the search box is used and how large the\nsearch index is.</p>\n<p>Defaults to: <code>149</code></p></div></div></div></div></div><div class='members-section'><div class='definedBy'>Defined By</div><h3 class='members-title icon-method'>Methods</h3><div class='subsection'><div id='method-clear' class='member first-child not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-method-clear' class='name expandable'>clear</a>( <span class='pre'></span> )</div><div class='description'><div class='short'>Clears the search box and reset the collection to its original. ...</div><div class='long'><p>Clears the search box and reset the collection to its original.</p>\n\n<p>If the collection is a PageableCollection, clearing will go back to the\nfirst page.</p>\n<p>Overrides: <a href='#!/api/Backgrid.Extension.ServerSideFilter-method-clear' rel='Backgrid.Extension.ServerSideFilter-method-clear' class='docClass'>Backgrid.Extension.ServerSideFilter.clear</a></p></div></div></div><div id='method-clearButton' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-method-clearButton' class='name expandable'>clearButton</a>( <span class='pre'></span> )</div><div class='description'><div class='short'>Returns the clear button. ...</div><div class='long'><p>Returns the clear button.</p>\n</div></div></div><div id='method-initialize' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-method-initialize' class='name expandable'>initialize</a>( <span class='pre'>options</span> )</div><div class='description'><div class='short'>Debounces the search and clear methods and makes a copy of the given\ncollection for searching. ...</div><div class='long'><p>Debounces the <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-method-search\" rel=\"Backgrid.Extension.ClientSideFilter-method-search\" class=\"docClass\">search</a> and <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-method-clear\" rel=\"Backgrid.Extension.ClientSideFilter-method-clear\" class=\"docClass\">clear</a> methods and makes a copy of the given\ncollection for searching.</p>\n<h3 class=\"pa\">Parameters</h3><ul><li><span class='pre'>options</span> : Object<div class='sub-desc'>\n<ul><li><span class='pre'>collection</span> : Backbone.Collection<div class='sub-desc'></div></li><li><span class='pre'>placeholder</span> : string (optional)<div class='sub-desc'></div></li><li><span class='pre'>fields</span> : string (optional)<div class='sub-desc'></div></li><li><span class='pre'>wait</span> : string (optional)<div class='sub-desc'><p>Defaults to: <code>149</code></p></div></li></ul></div></li></ul><p>Overrides: <a href='#!/api/Backgrid.Extension.ServerSideFilter-method-initialize' rel='Backgrid.Extension.ServerSideFilter-method-initialize' class='docClass'>Backgrid.Extension.ServerSideFilter.initialize</a></p></div></div></div><div id='method-makeMatcher' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-method-makeMatcher' class='name expandable'>makeMatcher</a>( <span class='pre'>query</span> ) : function(Backbone.Model):boolean</div><div class='description'><div class='short'>This default implementation takes a query string and returns a matcher\nfunction that looks for matches in the model's...</div><div class='long'><p>This default implementation takes a query string and returns a matcher\nfunction that looks for matches in the model's <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-property-fields\" rel=\"Backgrid.Extension.ClientSideFilter-property-fields\" class=\"docClass\">fields</a> or all of its\nfields if <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-property-fields\" rel=\"Backgrid.Extension.ClientSideFilter-property-fields\" class=\"docClass\">fields</a> is null, for any of the words in the query\ncase-insensitively using the regular expression object returned from</p>\n\n<h1>makeRegExp.</h1>\n\n<p>Most of time, you'd want to override the regular expression used for\nmatching. If so, please refer to the <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-method-makeRegExp\" rel=\"Backgrid.Extension.ClientSideFilter-method-makeRegExp\" class=\"docClass\">makeRegExp</a> documentation,\notherwise, you can override this method to return a custom matching\nfunction.</p>\n\n<p>Subclasses overriding this method must take care to conform to the\nsignature of the matcher function. The matcher function is a function\nthat takes a model as paramter and returns true if the model matches a\nsearch, or false otherwise.</p>\n\n<p>In addition, when the matcher function is called, its context will be\nbound to this ClientSideFilter object so it has access to the filter's\nattributes and methods.</p>\n<h3 class=\"pa\">Parameters</h3><ul><li><span class='pre'>query</span> : string<div class='sub-desc'><p>The search query in the search box.</p>\n</div></li></ul><h3 class='pa'>Returns</h3><ul><li><span class='pre'>function(Backbone.Model):boolean</span><div class='sub-desc'><p>A matching function.</p>\n</div></li></ul></div></div></div><div id='method-makeRegExp' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-method-makeRegExp' class='name expandable'>makeRegExp</a>( <span class='pre'>query</span> ) : RegExp</div><div class='description'><div class='short'>Constructs a Javascript regular expression object for makeMatcher. ...</div><div class='long'><p>Constructs a Javascript regular expression object for <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-method-makeMatcher\" rel=\"Backgrid.Extension.ClientSideFilter-method-makeMatcher\" class=\"docClass\">makeMatcher</a>.</p>\n\n<p>This default implementation takes a query string and returns a Javascript\nRegExp object that matches any of the words contained in the query string\ncase-insensitively. Override this method to return a different regular\nexpression matcher if this behavior is not desired.</p>\n<h3 class=\"pa\">Parameters</h3><ul><li><span class='pre'>query</span> : string<div class='sub-desc'><p>The search query in the search box.</p>\n</div></li></ul><h3 class='pa'>Returns</h3><ul><li><span class='pre'>RegExp</span><div class='sub-desc'><p>A RegExp object to match against model <a href=\"#!/api/Backgrid.Extension.ClientSideFilter-property-fields\" rel=\"Backgrid.Extension.ClientSideFilter-property-fields\" class=\"docClass\">fields</a>.</p>\n</div></li></ul></div></div></div><div id='method-render' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-method-render' class='name expandable'>render</a>( <span class='pre'></span> ) : <a href=\"#!/api/Backgrid.Extension.ServerSideFilter\" rel=\"Backgrid.Extension.ServerSideFilter\" class=\"docClass\">Backgrid.Extension.ServerSideFilter</a><strong class='chainable signature' >chainable</strong></div><div class='description'><div class='short'>Renders a search form with a text box, optionally with a placeholder and\na preset value if supplied during initializa...</div><div class='long'><p>Renders a search form with a text box, optionally with a placeholder and\na preset value if supplied during initialization.</p>\n<h3 class='pa'>Returns</h3><ul><li><span class='pre'><a href=\"#!/api/Backgrid.Extension.ServerSideFilter\" rel=\"Backgrid.Extension.ServerSideFilter\" class=\"docClass\">Backgrid.Extension.ServerSideFilter</a></span><div class='sub-desc'><p>this</p>\n</div></li></ul></div></div></div><div id='method-search' class='member  not-inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><span class='defined-in' rel='Backgrid.Extension.ClientSideFilter'>Backgrid.Extension.ClientSideFilter</span><br/></div><a href='#!/api/Backgrid.Extension.ClientSideFilter-method-search' class='name expandable'>search</a>( <span class='pre'></span> )</div><div class='description'><div class='short'>Takes the query from the search box, constructs a matcher with it and\nloops through collection looking for matches. ...</div><div class='long'><p>Takes the query from the search box, constructs a matcher with it and\nloops through collection looking for matches. Reset the given collection\nwhen all the matches have been found.</p>\n\n<p>If the collection is a PageableCollection, searching will go back to the\nfirst page.</p>\n<p>Overrides: <a href='#!/api/Backgrid.Extension.ServerSideFilter-method-search' rel='Backgrid.Extension.ServerSideFilter-method-search' class='docClass'>Backgrid.Extension.ServerSideFilter.search</a></p></div></div></div><div id='method-searchBox' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-method-searchBox' class='name expandable'>searchBox</a>( <span class='pre'></span> )</div><div class='description'><div class='short'>Returns the search input box. ...</div><div class='long'><p>Returns the search input box.</p>\n</div></div></div><div id='method-showClearButtonMaybe' class='member  inherited'><a href='#' class='side expandable'><span>&nbsp;</span></a><div class='title'><div class='meta'><a href='#!/api/Backgrid.Extension.ServerSideFilter' rel='Backgrid.Extension.ServerSideFilter' class='defined-in docClass'>Backgrid.Extension.ServerSideFilter</a><br/></div><a href='#!/api/Backgrid.Extension.ServerSideFilter-method-showClearButtonMaybe' class='name expandable'>showClearButtonMaybe</a>( <span class='pre'></span> )</div><div class='description'><div class='short'>Event handler. ...</div><div class='long'><p>Event handler. Show the clear button when the search box has text, hide\nit otherwise.</p>\n</div></div></div></div></div></div></div>"
});