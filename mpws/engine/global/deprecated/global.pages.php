<?php

    include_once ('global.site.php');

    $PAGE['PROD'] = SITEURL . 'product.php';
    $PAGE['CAT'] = SITEURL . 'category.php';
    $PAGE['ORIG'] = SITEURL . 'category.php';
    $PAGE['HOME'] = SITEURL . 'index.php';
    $PAGE['SHOP'] = SITEURL . 'shop.php';
    
    $GLOBALS['PAGE'] = $PAGE;

?>