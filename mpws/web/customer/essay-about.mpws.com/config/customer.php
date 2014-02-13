

    $customer['CUSTOMER'] = array();

    // define library the will be included
    // with custom mathods.
    // the name should be in the next format:
    // (echo -n 'www.customer.com' | md5sum) | sed 's/[0-9]*//g' + '.php'
    // it must be stored in the root customer's directory'
    $customer['INTERNAL_LIBRARY'] = 'abcfefccdccbe';

