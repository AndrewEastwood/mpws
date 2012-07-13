
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
    $customer['VALIDATOR']['DATAMAP']['ACCOUNT_UPDATE'] = array(
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
        'Current Password' => 'user_current_password',
        'NewPassword' => 'user_new_password',
        'NewPasswordConfirm' => 'user_new_password_confirm'
    );
    
    
    $customer['VALIDATOR']["FILTER"]['ORDER'] = array(
        'Title' => '',
        'PriceID' => '/\d+/',
        'Level' => '/High School|College|University/',
        'Format' => '/MLA|APA|Chicago|Turabian/',
        'Pages' => '/\d{1,2}/',
        'Sources' => '/\d{1,2}/',
        'Email' => '/(?=^.{1,50}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/'
    );
    
    $customer['VALIDATOR']["FILTER"]['ACCOUNT_CREATE'] = array(
        'Name' => '/^[a-z0-9_-\s\.]{3,35}$/',
        'Login' => '/^[a-z0-9_-]{3,15}$/',
        'Password' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/',
        'Email' => '/(?=^.{1,50}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/'
    );
    
    $customer['VALIDATOR']["FILTER"]['ACCOUNT_UPDATE'] = array(
        'Name' => '/^[a-z0-9_-\s\.]{3,35}$/',
        'Login' => '/^[a-z0-9_-]{3,15}$/',
        'Password' => '/^(?=^.{8,16}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/',
        'Email' => '/(?=^.{1,50}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}/'
    );