
    $default['MAIL'] = array();

    $default['MAIL']['WEBMASTER'] = array(
        'EMAIL' => 'webmaster@' . MPWS_CUSTOMER,
        'FROM' => 'Webmaster <webmaster@' . MPWS_CUSTOMER . '>',
        'CONTENT_TYPE' => 'text/html; charset=iso-8859-1'
    );

    $default['MAIL']['NOTIFY'] = array(
        'EMAIL' => 'no-reply@' . MPWS_CUSTOMER,
        'FROM' => 'Information <no-reply@' . MPWS_CUSTOMER . '>',
        'CONTENT_TYPE' => 'text/html; charset=iso-8859-1'
    );

    $default['MAIL']['SUPPORT'] = array(
        'EMAIL' => 'support@' . MPWS_CUSTOMER,
        'FROM' => 'Customer Support <support@' . MPWS_CUSTOMER . '>',
        'CONTENT_TYPE' => 'text/html; charset=iso-8859-1'
    );
    
    $default['MAIL']['ACTION_TRIGGERS'] = array(
        /*
        Action Collection To Email
        'ON_SOME_ACTION' => $default['MAIL']['SUPPORT']
        
        */
    );


    $default['MAIL']['URLS'] = array();
