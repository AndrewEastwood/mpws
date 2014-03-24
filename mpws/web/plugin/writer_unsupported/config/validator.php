
    $plugin['VALIDATOR'] = array();
    $plugin['VALIDATOR']["DATAMAP"] = array();
    $plugin['VALIDATOR']["FILTER"] = array();


    $plugin['VALIDATOR']["DATAMAP"]['WRITERS'] = array(
        'Name' => 'writer_name',
        'Login' => 'writer_login',
        'Password' => 'writer_password',
        'Subjects' => 'writer_subjects',
        'CardNumber' => 'writer_cardnumber',
        'University' => 'writer_university',
        'Email' => 'writer_email',
        'IM' => 'writer_im',
        'Phone' => 'writer_phone',
        'Active' => 'writer_active'
    );

    $plugin['VALIDATOR']["DATAMAP"]['MESSAGES'] = array(
        'To' => 'messages_to',
        'Subject' => 'messages_subject',
        'Message' => 'messages_message',
    );

    $plugin['VALIDATOR']["DATAMAP"]['SUBJECTS'] = array(
        'Name' => 'subject_name'
    );

    $plugin['VALIDATOR']["DATAMAP"]['DOCUMENTS'] = array(
        'Name' => 'document_name'
    );

    $plugin['VALIDATOR']["DATAMAP"]['SALE'] = array(
        'Title' => 'sale_title',
        'Description' => 'sale_description',
        'Sample' => 'sale_sample',
        'Pages' => 'sale_pages',
        'Price' => 'sale_price',
        'DocumentURL' => 'sale_documenturl'
    );
    
    $plugin['VALIDATOR']["DATAMAP"]['PRICES'] = array(
        'Name' => 'price_name',
        'Price' => 'price_value',
        'Hours' => 'price_hours',
        'Weeks' => 'price_weeks'
    );

    /******** FILTERING ********/

    $plugin['VALIDATOR']["FILTER"]['WRITERS'] = array(
        'Name' => '/^(?=^.{8,30}$)[a-zA-Z\s.]+$/',
        'Login' => '/^[a-z0-9_-]{3,15}$/',
        'Password' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/',
        //'CardNumber' => '@validateCreditCard',
        'Email' => '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/'
    );

    $plugin['VALIDATOR']["FILTER"]['MESSAGES'] = array(
        'Subject' => '/^(?=^.{10,50}$)[a-zA-Z\s.?!*+=-_]+$/',
        'Message' => '/^(?=^.{10,350}$)[a-zA-Z\s.]+$/'
    );

    $plugin['VALIDATOR']["FILTER"]['SUBJECTS'] = array(
        'Name' => '/^(?=^.{10,150}$)[a-zA-Z\s.?!*+=-_]+$/'
    );

    $plugin['VALIDATOR']["FILTER"]['DOCUMENTS'] = array(
        'Name' => '/^(?=^.{10,150}$)[a-zA-Z\s.?!*+=-_]+$/'
    );
    
    $plugin['VALIDATOR']["FILTER"]['SALE'] = array(
        'Title' => '/^.{3,50}$/',
        'Pages' => '/\d+/',
        'Price' => '/^\d+\.\d{0,2}?$/'
    );

    $plugin['VALIDATOR']["FILTER"]['PRICES'] = array(
        //'Name' => '/^(?=^.{4,40}$)[a-zA-Z\s.?!*+=-_\&\/]+$/',
        'Price' => '/^\d+\.\d{2}?$/',
        'Hours' => '/\d+/',
        'Weeks' => '/\d+/'
    );

    // email /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/
    // length /^.{3,20}$/
    // login /^[a-z0-9_-]{3,15}$/
    // pwd /^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/
    // price /^\d+(\.\d{2})?$/

