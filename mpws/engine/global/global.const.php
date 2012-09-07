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

    define ("DS", "/");

    define ("BS", "_", true);
    
    define ("EQ", "=", true);

    define ("US", "..", true);

    // Object Types
    
    define ("OBJECT_T_NONE", '', true);
    define ("OBJECT_T_PLUGIN", 'plugin', true);
    define ("OBJECT_T_CUSTOMER", "customer", true);
    define ("OBJECT_T_CONTEXT", "context", true);
 
    // Scripts
    
    define("EXT_SCRIPT", DOT."php");

?>
