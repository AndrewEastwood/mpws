
    $plugin['VALIDATOR'] = array();
    $plugin['VALIDATOR']["DATAMAP"] = array();
    $plugin['VALIDATOR']["FILTER"] = array();


    $plugin['VALIDATOR']["DATAMAP"]['CONTENT'] = array(
        'Property' => 'content_property',
        'PageOwner' => 'content_pageowner',
        'Value' => 'content_value'
    );

    /******** FILTERING ********/

    $plugin['VALIDATOR']["FILTER"]['CONTENT'] = array(
        'Property' => '/[a-zA-Z0-9-_]+/',
        'PageOwner' => '/[a-zA-Z0-9-_]+/'
    );

    // email /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/
    // length /^.{3,20}$/
    // login /^[a-z0-9_-]{3,15}$/
    // pwd /^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/
    // price /^\d+(\.\d{2})?$/

