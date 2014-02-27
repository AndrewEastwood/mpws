<?php

    header('Content-Type: application/json');

    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    $customer = libraryRequest::getValue('customer');
    $langfile = libraryRequest::getValue('lang');

    if (MPWS_ENV === 'DEV')
        $layoutCustomer = glGetFullPath('web', 'customer', $customer, 'static', 'nls', $langfile);
    else
        $layoutCustomer = glGetFullPath('web', 'build', 'customer', $customer, 'static', 'nls', $langfile);

    // echo $layoutCustomer;

    if (file_exists($layoutCustomer))
        echo file_get_contents($layoutCustomer);
    else
        echo "{}";

?>