
    $plugin['QUERY'] = array();

    $plugin['QUERY']['MPWS_USERS_ALL'] = 'SELECT * FROM mpws_users';
    $plugin['QUERY']['MPWS_USERS_BLOCKED'] = 'SELECT * FROM mpws_users WHERE Active = 0';
    $plugin['QUERY']['MPWS_USERS_OFFLINE'] = 'SELECT * FROM mpws_users WHERE IsOnline = 0';
    $plugin['QUERY']['MPWS_USERS_ACTIVE'] = 'SELECT * FROM mpws_users WHERE Active = 1';
    $plugin['QUERY']['MPWS_USERS_ONLINE'] = 'SELECT * FROM mpws_users WHERE IsOnline = 1';
