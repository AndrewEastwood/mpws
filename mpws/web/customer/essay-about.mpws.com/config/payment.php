
    $customer['PAYMENT'] = array();
    
    // 2checkout.com
    // product information
    $customer['PAYMENT']['2CO'] = array(
        'FORM' => array(
            'sid' => '1799160',
            'quantity' => '1'
        ),
        'API' => array(
            'USER' => 'DemoAPI',
            'PWD' => 'Welcome1!',
            'METHODS' => array(
                'list_products' => 'https://www.2checkout.com/api/products/list_products',
                'create_product' => 'https://www.2checkout.com/api/products/create_product',
                'purchase' => 'https://www.2checkout.com/checkout/spurchase'
            )
        ),
        'PRODUCT_CATEGORY' => 15
    );

/*



middle_initial=&sid=1799160&key=174604886ACD1C58A3CF55926E5E47B3&state=NY&email=soulcor%40gmail.com&order_number=4766009260&product_description=Essay.%0Ademo+price.%0ATotal+pages%3A+1&lang=en&invoice_id=4766009269&total=0.02&credit_card_processed=Y&zip=10001&merchant_product_id1=B52&quantity1=1&fixed=N&cart_weight=0&last_name=Last&city=New+York&street_address=123+Anywhere+St.&product_id=17&country=USA&merchant_order_id=a485d7941adaf51918e9e07a71883eb0&ip_country=Ukraine&product_description1=Essay.%0Ademo+sale+1.%0ATotal+pages%3A+2&product_id1=18&demo=Y&quantity=1&pay_method=CC&cart_tangible=N&phone=123-456-7890+&merchant_product_id=E81&street_address2=&first_name=First&card_holder_name=First++Last


*/