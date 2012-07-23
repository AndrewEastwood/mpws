
    $customer['MAIL'] = array();

    $customer['MAIL']['URLS'] = array(
        'LOGIN' => 'http://' . $_SERVER['SERVER_NAME'] . '/page/account.html',
        'ACTIVATE' => 'http://' . $_SERVER['SERVER_NAME'] . '/page/activate.html?digest=',
        'TOOLBOX_ORDER_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/mpws/writer.html?display=orders&amp;action=details&amp;token='
    );

    /* Action Collection To Email */
    $customer['MAIL']['ACTION_TRIGGERS']['ON_NEW_ORDER'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_STATUS_CHANGED'] = $default['MAIL']['INFO'];