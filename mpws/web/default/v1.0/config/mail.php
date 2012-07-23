
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

    $default['MAIL']['INFO'] = array(
        'TO' => 'info@essay-about.com',
        'EMAIL' => 'info@essay-about.com',
        'FROM' => 'no-reply@essay-about.com',
        'NAME' => 'Info',
        'CONTENT_TYPE' => 'text/html; charset=iso-8859-1'
    );
    
    $default['MAIL']['ACTION_TRIGGERS'] = array();

    $default['MAIL']['URLS'] = array();
