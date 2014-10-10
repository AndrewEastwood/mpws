<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    /*
     * jQuery File Upload Plugin PHP Example 5.14
     * https://github.com/blueimp/jQuery-File-Upload
     *
     * Copyright 2010, Sebastian Tschan
     * https://blueimp.net
     *
     * Licensed under the MIT license:
     * http://www.opensource.org/licenses/MIT
     */

    // error_reporting(E_ALL | E_STRICT);
    // require('libraryUploadHandler.php');
    $options = array(
        'upload_dir' => DR . 'uploads/temp/',
        'upload_url' => '/content/temp/',
        'correct_image_extensions' => true
    );
    // var_dump($options);
    $upload_handler = new libraryUploadHandler($options);
    // var_dump($upload_handler);

?>