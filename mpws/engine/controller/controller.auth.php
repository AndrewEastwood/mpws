<?php

    header('Content-Type: application/json; charset=utf-8');
    // set request realm
    define ('MPWS_REQUEST', 'AUTH');
    // start session
    session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    // do not put $customer into global scope
    function response () {
        $customer = new libraryCustomer();
        $responce = $customer->getAccount()->signin();
        // var_dump($responce->toNative());
        return $responce;
    }

    $data = response();
    echo $data->toJSON();

?>