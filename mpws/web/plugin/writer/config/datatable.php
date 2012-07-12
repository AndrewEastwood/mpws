
    $plugin['DATATABLE'] = array();

    $plugin['DATATABLE']['HOME'] = array(
        'home' => '0',
        'orders' => '10',
        'messages' => '10',
        'writers' => '2',
        'users' => '10',
        'balancer' => '10'
    );

    $plugin['DATATABLE']['PRICES'] = array(
        'TABLE' => 'writer_prices',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_prices_',
        'LIMIT' => '30',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );

    $plugin['DATATABLE']['DOCUMENTS'] = array(
        'TABLE' => 'writer_documents',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_documents_',
        'LIMIT' => '40',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );

    $plugin['DATATABLE']['SUBJECTS'] = array(
        'TABLE' => 'writer_subjects',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_subjects_',
        'LIMIT' => '40',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );

    $plugin['DATATABLE']['MESSAGES'] = array(
        'TABLE' => 'writer_messages',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_messages_',
        'LIMIT' => '30',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );

    $plugin['DATATABLE']['WRITERS'] = array(
        'TABLE' => 'writer_writers',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_writer_',
        'LIMIT' => '10',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );

    $plugin['DATATABLE']['STUDENTS'] = array(
        'TABLE' => 'writer_students',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_student_',
        'LIMIT' => '10',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );

    $plugin['DATATABLE']['ORDERS'] = array(
        'TABLE' => 'writer_orders',
        'PAGEKEY' => 'pg',
        'SORTKEY' => 'sort',
        'SEARCH_KEY_PREFIX' => 'searchbox_order_',
        'LIMIT' => '30',
        'SIZE' => '3',
        'EDGES' => 'FIRST-PREV-NEXT-LAST'
    );
