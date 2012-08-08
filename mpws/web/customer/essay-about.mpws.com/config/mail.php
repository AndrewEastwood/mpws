
    $customer['MAIL'] = array();

    $customer['MAIL']['URLS'] = array(
        'LOGIN' => 'http://' . $_SERVER['SERVER_NAME'] . '/page/account.html',
        'ACTIVATE' => 'http://' . $_SERVER['SERVER_NAME'] . '/page/activate.html?digest=',
        'TOOLBOX_ORDER_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/mpws/writer.html?display=orders&amp;action=details&amp;token=',
        'TOOLBOX_ORDER_LINK_OID' => 'http://' . $_SERVER['SERVER_NAME'] . '/mpws/writer.html?display=orders&amp;action=details&amp;oid=',
        'TOOLBOX_STUDENT_LINK_OID' => 'http://' . $_SERVER['SERVER_NAME'] . '/mpws/writer.html?display=students&amp;action=details&amp;oid=',
        'TOOLBOX_SALE_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/mpws/writer.html?display=sales&amp;action=details&amp;token=',
        'ACCOUNT_ORDER_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/page/account.html?display=orders&amp;action=details&amp;oid='
    );
    
    /* Other IMs */
    $customer['MAIL']['IM'] = array(
        'SKYPE' => 'skype:essay_about_teamlead?chat'
    );

    /* Action Collection To Email */
    $customer['MAIL']['ACTION_TRIGGERS']['ON_NEW_ORDER'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_NEW_SALE'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_STATUS_CHANGED'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_NEW_REGISTRATION'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_NEW_AUTOUSER'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_ACCEPTED'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_REJECTED'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_TO_REVIEW'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_TO_REWORK'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_TO_REFUND'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_REOPENED'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_CLOSED'] = $default['MAIL']['INFO'];
    $customer['MAIL']['ACTION_TRIGGERS']['ON_ORDER_COMMENTED'] = $default['MAIL']['INFO'];
