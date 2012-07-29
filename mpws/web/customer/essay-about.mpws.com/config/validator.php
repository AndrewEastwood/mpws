
    $customer['VALIDATOR'] = array();
    $customer['VALIDATOR']['DATAMAP'] = array();
    $customer['VALIDATOR']["FILTER"] = array();
    
    // db field names to html form fields
    $customer['VALIDATOR']['DATAMAP']['ORDER'] = array(
        'Title' => 'order_title',
        'Description' => 'order_notes',
        'PriceID' => 'order_price',
        'Level' => 'order_level',
        'Format' => 'order_format',
        'Pages' => 'order_pages',
        'Sources' => 'order_sources',
        'Email' => 'order_email',
        'SourceLinks' => 'order_source_links',
        'TimeZone' => 'order_timezone',
        'DateDeadline' => 'order_datedeadline',
        'DocumentID' => 'order_doc',
        'SubjectID' => 'order_subject'
    );
    
    // db field names to html form fields
    $customer['VALIDATOR']['DATAMAP']['ACCOUNT_CREATE'] = array(
        'Name' => 'user_name',
        'Login' => 'user_login',
        'Password' => 'user_pwd',
        'Password2' => 'user_pwd_dbl',
        'Email' => 'user_email'
    );
    
    // db field names to html form fields
    $customer['VALIDATOR']['DATAMAP']['STUDENT_ACCOUNT_UPDATE'] = array(
        'Name' => 'user_name',
        'Login' => 'user_login',
        'Email' => 'user_email',
        'Phone' => 'user_phone',
        'Billing_Address' => 'user_billing_address',
        'Billing_City' => 'user_billing_city',
        'Billing_Country' => 'user_billing_country',
        'Billing_Email' => 'user_billing_email',
        'Billing_FirstName' => 'user_billing_firstname',
        'Billing_LastName' => 'user_billing_lastname',
        'Billing_Phone' => 'user_billing_phone',
        'Billing_PostalCode' => 'user_billing_postalcode',
        'Billing_State' => 'user_billing_state',
        'TimeZone' => 'user_timezone'
    );
    
    $customer['VALIDATOR']['DATAMAP']['WRITER_ACCOUNT_UPDATE'] = array(
        'Name' => 'user_name',
        'Login' => 'user_login',
        'Email' => 'user_email',
        'Phone' => 'user_phone',
        'Subjects' => 'user_subjects',
        'CardNumber' => 'user_cardnumber',
        'CardType' => 'user_cardtype',
        'University' => 'user_university',
        'IM' => 'user_im',
        'TimeZone' => 'user_timezone'
    );
    
    $customer['VALIDATOR']['DATAMAP']['ACCOUNT_PWD_UPDATE'] = array(
        'CurrentPassword' => 'user_current_password',
        'NewPassword' => 'user_new_password',
        'NewPasswordConfirm' => 'user_new_password_confirm'
    );
    
    $customer['VALIDATOR']["DATAMAP"]['MESSAGES'] = array(
        'Subject' => 'messages_subject',
        'Message' => 'messages_message',
    );
    
    $customer['VALIDATOR']["FILTER"]['ORDER'] = array(
        'Title' => '/^[\[\]=,\?!&@~\{\}\+\'\.\*()a-zA-Z0-9_-\s]{3,35}$/',
        'PriceID' => '/\d+/',
        'Level' => '/High School|College|University/',
        'Format' => '/MLA|APA|Chicago|Turabian/',
        'Pages' => '/\d{1,2}/',
        //'Sources' => '/\d{1,2}/',
        'Email' => '/(?=^.{1,50}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/',
        'DateDeadline' => '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/'
    );
    
    $customer['VALIDATOR']["FILTER"]['ACCOUNT_CREATE'] = array(
        'Name' => '/^[a-zA-Z0-9_-\s\.]{3,35}$/',
        'Login' => '/^[a-zA-Z0-9_-]{3,15}$/',
        'Password' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/',
        'Password2' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/',
        'Email' => '/(?=^.{1,50}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/'
    );
    
    $customer['VALIDATOR']["FILTER"]['ACCOUNT_UPDATE'] = array(
        'Name' => '/^[a-zA-Z0-9_-\s\.]{3,35}$/',
        'Login' => '/^[a-zA-Z0-9_-]{3,15}$/',
        'Email' => '/(?=^.{1,50}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/'
    );

    $customer['VALIDATOR']["FILTER"]['ACCOUNT_PWD_UPDATE'] = array(
        'NewPassword' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/',
        'NewPasswordConfirm' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/'
    );
    

    $customer['VALIDATOR']["FILTER"]['MESSAGES'] = array();