<?php

    // Text Formatters

    define ("NLN", "\n");

    define ("TAB", "    ");

    // HyperText Formatters

    define ("DQUO", '"');

    define ("SQOU", "'");

    // Log Formatters

    define ("RUNLOG", "[mpws:] %s".NLN);

    define ("HRUNLOG", "[mpws:] %s <br />".NLN);

    // Path Formatters

    define ("DOT", ".");
    
    define ("DOG", "@");
    
    define ("SHARP", "#");
    
    define ("STAR", "*");

    define ("DS", "/");

    define ("BS", "_", true);
    
    define ("EQ", "=", true);

    define ("US", "..", true);
    
    define ("COLON", ":", true);
    
    define ("EXPLODE", "#EXPLODE#", true);

    // Object Types
    
    define ("OBJECT_T_NONE", '', true);
    define ("OBJECT_T_PLUGIN", 'plugin', true);
    define ("OBJECT_T_CUSTOMER", "customer", true);
    define ("OBJECT_T_CONTEXT", "context", true);
    define ("OBJECT_T_DEFAULT", "default", true);
 
    // Connection Type
    
    define ("T_CONNECT_DB", 'database', true);
    define ("T_CONNECT_ORM", 'orm', true);
    
    // Scripts
    
    define("EXT_SCRIPT", DOT."php");
    define("EXT_TEMPLATE", DOT."html");
    define("EXT_JS", DOT."js");
    
    // GLOB SELECTORS
    
    define("gEXT_ALL_SCRIPT", DS.'*'.DOT."php");
    define("gEXT_ALL_TEMPLATE", DS.'*'.DOT."html");
    define("gEXT_ALL_JS", DS.'*'.DOT."js");
    
    // render components
    
    define ("renderFLD_NAME", 'mpws'.BS.'field'.BS);

    // data formats
    
    define("fmtDEFAULT", "DEFAULT");
    define("fmtJSON", "JSON");
    define("fmtARRAY", "ARRAY");
    define("fmtHASH", "HASH");
    define("fmtSTRING", "STRING");

    define("MERGE_MODE_REPLACE", 0);
    define("MERGE_MODE_APPEND", 1);
    define("MERGE_MODE_PREPEND", -1);

    define("DIR_WEB", 'web');
    define("DIR_CUSTOMER", 'customer');
    define("DIR_DEFAULT", 'default');
    define("DIR_PLUGIN", 'plugin');
    define("DIR_UPLOADS", 'uploads');
    define("DIR_TEMP", 'temp');

?>
