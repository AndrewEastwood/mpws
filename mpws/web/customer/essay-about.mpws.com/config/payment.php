
    $customer['PAYMENT'] = array();
    
    // 2checkout.com
    // product information
    $customer['PAYMENT']['2CO'] = array(
        'FORM' => array(
            //'sid' => '1799160',
            'sid' => '1788351',
            'quantity' => '1',
            'fixed' => 'Y',
            'skip_landing' => 'Y'
        ),
        'API' => array(
            //'USER' => 'DemoAPI',
            //'PWD' => 'Welcome1!',
            'USER' => 'litopisAPI',
            'PWD' => 'Ka374852',
            'METHODS' => array(
                'list_products' => 'https://www.2checkout.com/api/products/list_products',
                'create_product' => 'https://www.2checkout.com/api/products/create_product',
                'purchase' => 'https://www.2checkout.com/checkout/spurchase'
            )
        ),
        'PRODUCT_CATEGORY' => 15
    );
