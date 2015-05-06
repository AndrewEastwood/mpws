<?php
namespace static_\plugins\shop\api;

use \engine\lib\uploadHandler as JqUploadLib;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use PHPExcel as PHPExcel;
use PHPExcel_IOFactory as PHPExcel_IOFactory;
use PHPExcel_Cell_DataValidation as PHPExcel_Cell_DataValidation;
use PHPExcel_Shared_File as PHPExcel_Shared_File;
use PHPExcel_Style_Color as PHPExcel_Style_Color;
use PHPExcel_Style_Border as PHPExcel_Style_Border;

class feeds {

    public function getDirNameFeeds () {
        return 'feeds';
    }

    public function getUploadedFeedName () {
        return 'import_' . date('Ymd_His');
    }

    public function getGeneratedFeedName () {
        return 'gen_' . date('Ymd_His');
    }

    public function getGeneratedFeedDownloadLink ($name) {
        return $this->getFeedsUploadDir() . $name;
    }

    public function getFeedsUploadInnerDir () {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $path = Path::createDirPath($customer['HostName'], 'shop', $this->getDirNameFeeds());
        return $path;
    }

    public function getFeedsUploadDir () {
        return Path::getUploadDirectory($this->getFeedsUploadInnerDir());
    }

    public function getFeedFilePathByName ($feedName) {
        return $this->getFeedsUploadDir() . $feedName . '.xls';
    }

    public function getFeedLogFilePathByName ($feedName) {
        return $this->getFeedsUploadDir() . $feedName . '.log';
    }

    public function getGeneratedFeedsFilesList () {
        return glob(Path::rootPath() . $this->getFeedsUploadDir() . 'gen_*\.xls');
    }

    public function getUploadedFeedsFilesList () {
        return glob(Path::rootPath() . $this->getFeedsUploadDir() . 'import_*\.xls');
    }

    private function __adjustFeedItem ($feedFilePath) {
        $pInfo = pathinfo($feedFilePath);
        $ftime = filectime($feedFilePath);
        $task = API::getAPI('system:tasks')->isTaskAdded('shop', 'importProductFeed', $pInfo['filename']);
        $feedLogPath = Path::rootPath() . $this->getFeedLogFilePathByName($pInfo['filename']);
        // $isActive = in_array($pInfo['filename'], $runningFeedNames);
        // $isCompleted = in_array($pInfo['filename'], $completeFeedNames);
        // $isAdded = in_array($pInfo['filename'], $newFeedNames);
        // $isCanceled = in_array($pInfo['filename'], $canceledFeedNames);
        $isScheduled = $task['Scheduled'];
        $isRunning = $task['IsRunning'];
        $isCompleted = $task['Complete'];
        $isCanceled = $task['ManualCancel'];
        $log = @file_get_contents($feedLogPath);
        return array(
            'ID' => md5($pInfo['filename']),
            'type' => 'uploaded',
            'time' => $ftime,
            'timeFormatted' => date('Y-m-d H:i:s', $ftime),
            'name' => $pInfo['filename'],
            'new' => !$isScheduled && !$isRunning && !$isCompleted && !$isCanceled,
            'scheduled' => $task['Scheduled'],
            'running' => $task['IsRunning'],
            'complete' => $task['Complete'],
            'canceled' => $task['ManualCancel'],
            'results' => $task['Result'] . PHP_EOL . $log,
            // 'canBeScheduled' => !$task['scheduled'],
            'status' => $isRunning ? 'active' : ($isCompleted ? 'done' : ($isCanceled ? 'canceled' : ($isScheduled ? 'scheduled' : 'new'))),
            'link' => $this->getGeneratedFeedDownloadLink($pInfo['basename'])
        );
    }

    public function getFeeds () {
        global $app;
        $attempts = 20;
        $feeds = array();

        do {
            $listFeedsGenerated = $this->getGeneratedFeedsFilesList();
            if (count($listFeedsGenerated) > 10) {
                unlink($listFeedsGenerated[0]);
            }
            $attempts--;
        } while (count($listFeedsGenerated) > 10 && $attempts > 0);

        $attempts = 20;
        do {
            $listFeedsUploaded = $this->getUploadedFeedsFilesList();
            if (count($listFeedsUploaded) > 10) {
                $pInfo = pathinfo($listFeedsUploaded[0]);
                $success = unlink($listFeedsUploaded[0]);
                if ($success) {
                    API::getAPI('system:tasks')->deleteTaskByParams('shop', 'importProductFeed', $pInfo['filename']);
                }
                // clear log
                $feedLogPath = Path::rootPath() . $this->getFeedLogFilePathByName($pInfo['filename']);
                if (file_exists($feedLogPath)) {
                    unlink($feedLogPath);
                }
            }
            $attempts--;
        } while (count($listFeedsUploaded) > 10 && $attempts > 0);

        if ($listFeedsGenerated)
            foreach ($listFeedsGenerated as $value) {
                $pInfo = pathinfo($value);
                $ftime = filectime($value);
                $feeds[] = array(
                    'ID' => md5($pInfo['filename']),
                    'type' => 'generated',
                    'time' => $ftime,
                    'timeFormatted' => date('Y-m-d H:i:s', $ftime),
                    'name' => $pInfo['filename'],
                    'link' => $this->getGeneratedFeedDownloadLink($pInfo['basename'])
                );
            }
        if ($listFeedsUploaded) {
            // $activeTasks = API::getAPI('system:tasks')->getActiveTasksByGroupName('shop');
            // $completeTasks = API::getAPI('system:tasks')->getCompletedTasksByGroupName('shop');
            // $newTasks = API::getAPI('system:tasks')->getNewTasksByGroupName('shop');
            // $canceledTasks = API::getAPI('system:tasks')->getCanceledTasksByGroupName('shop');
            // $runningFeedNames = array();
            // $completeFeedNames = array();
            // $newFeedNames = array();
            // $canceledFeedNames = array();
            // if ($activeTasks)
            //     foreach ($activeTasks as $taskItem) {
            //         $runningFeedNames[] = $taskItem['Params'];
            //     }
            // if ($completeTasks)
            //     foreach ($completeTasks as $taskItem) {
            //         $completeFeedNames[] = $taskItem['Params'];
            //     }
            // if ($newTasks)
            //     foreach ($newTasks as $taskItem) {
            //         $newFeedNames[] = $taskItem['Params'];
            //     }
            // if ($canceledTasks)
            //     foreach ($canceledTasks as $taskItem) {
            //         $canceledFeedNames[] = $taskItem['Params'];
            //     }
            // var_dump($runningFeedNames);
            foreach ($listFeedsUploaded as $value) {
                $feeds[] = $this->__adjustFeedItem($value);
            }
        }
            // $feeds['active'] = $activeTasks;
        return $feeds;
    }
    public function importProductFeed ($name) {
        global $app;

        $results = array();
        $task = API::getAPI('system:tasks')->isTaskAdded('shop', 'importProductFeed', $name);

        if (ob_get_level() == 0) ob_start();

        $customer = API::getApi('system:customers')->getRuntimeCustomer();
        $feedPath = Path::rootPath() . $this->getFeedFilePathByName($name);
        $feedLogPath = Path::rootPath() . $this->getFeedLogFilePathByName($name);
        // $objPHPExcel = new PHPExcel();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load($feedPath);
        // $objPHPExcel->setReadDataOnly(true);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
        $headingsArray = $headingsArray[1];


        $r = -1;
        $namedDataArray = array();
        for ($row = 2; $row <= $highestRow; ++$row) {
            $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, true, true);
            if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                ++$r;
                foreach($headingsArray as $columnKey => $columnHeading) {
                    $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
                }
            }
        }

        $errors = array();
        $info = array();
        $parsedProducts = array();
        $addedCount = 0;
        $updatedCount = 0;
        $errorCount = 0;
        $total = count($namedDataArray);
        $processed = 0;

        $keysToEncode = array('Name', 'Model', 'CategoryName', 'OriginName',
            'Description', 'Features', 'TAGS', 'WARRANTY');

        // archive all products if Status column is added in feed
        if (in_array('Status', $headingsArray)) {
            $info[] = 'Archiving all prducts';
            API::getAPI('shop:products')->archiveAllProducts();
        }

        // convert to native structure
        foreach ($namedDataArray as &$rawProductData) {

            $currentImportResult = array();
            //-- echo "processing product " . $rawProductData['Name'] . PHP_EOL;
            ob_flush();
            flush();

            $productItem = array();
            $imagesUrls = array();
            // required fileds
            $productItem['Name'] = trim($rawProductData['Name']);
            $productItem['Model'] = trim($rawProductData['Model']);
            $productItem['OriginName'] = strtoupper(trim($rawProductData['OriginName']));
            // when no status field then we assume all importing products are active
            if (isset($rawProductData['Status'])) {
                $productItem['Status'] = trim($rawProductData['Status']);
            } else {
                $productItem['Status'] = 'ACTIVE';
            }
            // optional fields
            if (isset($rawProductData['CategoryName'])) {
                $productItem['CategoryName'] = trim($rawProductData['CategoryName']);
            }
            if (isset($rawProductData['Price'])) {
                $productItem['Price'] = floatval(trim($rawProductData['Price']));
            }
            if (isset($rawProductData['IsPromo'])) {
                $productItem['IsPromo'] = !empty($rawProductData['IsPromo']) && trim($rawProductData['IsPromo']) === '+';
            }
            if (isset($rawProductData['IsOffer'])) {
                $productItem['IsOffer'] = !empty($rawProductData['IsOffer']) && trim($rawProductData['IsOffer']) === '+';
            }
            if (isset($rawProductData['IsFeatured'])) {
                $productItem['IsFeatured'] = !empty($rawProductData['IsFeatured']) && trim($rawProductData['IsFeatured']) === '+';
            }
            if (isset($rawProductData['TAGS'])) {
                $productItem['TAGS'] = $rawProductData['TAGS'];
            }
            if (isset($rawProductData['Synopsis'])) {
                $productItem['Synopsis'] = trim($rawProductData['Synopsis']);
            }
            if (isset($rawProductData['Description'])) {
                $productItem['Description'] = trim($rawProductData['Description']);
            }
            if (isset($rawProductData['WARRANTY'])) {
                $productItem['WARRANTY'] = trim($rawProductData['WARRANTY']);
            }
            // set images
            if (isset($rawProductData['Images'])) {
                $imagesUrls = explode(PHP_EOL, trim($rawProductData['Images']));
            } else {
                if (isset($rawProductData['Image1'])) {
                    $imagesUrls[] = trim($rawProductData['Image1']);
                }
                if (isset($rawProductData['Image2'])) {
                    $imagesUrls[] = trim($rawProductData['Image2']);
                }
                if (isset($rawProductData['Image3'])) {
                    $imagesUrls[] = trim($rawProductData['Image3']);
                }
                if (isset($rawProductData['Image4'])) {
                    $imagesUrls[] = trim($rawProductData['Image4']);
                }
                if (isset($rawProductData['Image5'])) {
                    $imagesUrls[] = trim($rawProductData['Image5']);
                }
            }

            // $currentImportResult[] = "[INFO] " . "set encoding";
            //-- echo "[INFO] " . "set encoding" . PHP_EOL;
            foreach ($keysToEncode as $key) {
                if (isset($productItem[$key])) {
                    $productItem[$key] = mb_convert_encoding((string)$productItem[$key], 'UTF-8', mb_list_encodings());
                }
            }

            // var_dump($productItem);
            if (empty($productItem['OriginName'])) {
                $errors[] = 'No OriginName for ' . $productItem['Name'] . ' ' . $productItem['Model'];
                $errorCount++;
                continue;
            }

            // $currentImportResult[] = "[INFO] " . "adjusting features";
            //-- echo "[INFO] " . "adjusting features" . PHP_EOL;
            if (isset($rawProductData['Features'])) {
                $featureChunks = explode('|', trim($rawProductData['Features']));
                $features = array();
                foreach ($featureChunks as $featureChunkItem) {
                    $featureKeyValue = explode('=', $featureChunkItem);
                    if (count($featureKeyValue) !== 2) {
                        $errors[] = 'Unable to parse feature chunk: ' . $featureChunkItem;
                    } else {
                        $features[$featureKeyValue[0]] = $featureKeyValue[1];
                    }
                }
                $productItem['Features'] = $features;
            }

            if (!empty($imagesUrls)) {
                // $currentImportResult[] = "[INFO] " . "downloading images started";
                //-- echo "[INFO] " . "downloading images" . PHP_EOL;
                // var_dump($rawProductData['Images']);
                $images = array();
                $appSettingsUrls = $app->getSettings('urls');
                $options = array(
                    'script_url' =>  $appSettingsUrls['upload'],
                    'download_via_php' => true,
                    'web_import_temp_dir' => Path::rootPath() . Path::getAppTemporaryDirectory(),
                    'upload_dir' => Path::rootPath() . Path::getUploadTemporaryDirectory(),
                    'print_response' => false,
                    'use_unique_hash_for_names' => true,
                    'correct_image_extensions' => true,
                    'mkdir_mode' => 0777
                );
                $upload_handler = new JqUploadLib($options, false);
                foreach ($imagesUrls as $imgUrl) {
                    if (empty($imgUrl)) {
                        continue;
                    }
                    $urlInfo = parse_url($imgUrl);
                    $pInfo = pathinfo($urlInfo['path']);
                    // var_dump($urlInfo['host'], $customer['HostName']);
                    if (!empty($urlInfo['host'])) {
                        if ($urlInfo['host'] !== $customer['HostName']) {
                            // $imagesToDownload[] = $imgUrl;
                            $currentImportResult[] = "[INFO] " . "importing image: " . $imgUrl;
                            //-- echo "[INFO] " . "downloading image" . $imgUrl . PHP_EOL;
                            set_time_limit(999);
                            echo '# ... importing image ' . $imgUrl;
                            $res = null;
                            try {
                                $res = $upload_handler->importFromUrl($imgUrl, false);
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                                echo " [ERROR]";
                            }
                            if (!empty($res['web'])) {
                                foreach ($res['web'] as $impageUploadInfo) {
                                    $images[] = $impageUploadInfo->name;
                                }
                                echo " [OK]";
                            } else {
                                echo " [ERROR]";
                            }
                            echo PHP_EOL;
                        } else {
                            // add more checks for product id and\or use product ExKeys for folder names
                            $images[] = $pInfo['basename'];
                        }
                    }
                    // currently we support only 5 images
                    if (count($images) >= 5) {
                        break;
                    }
                }
                // var_dump($imagesToDownload);
                // download image here
                // $urls = array();
                // $urls[] = 'http://upload.wikimedia.org/wikipedia/commons/6/66/Android_robot.png';
                // $urls[] = 'http://www.notebookcheck.net/uploads/tx_nbc2/delXPS14.jpg';
                // var_dump($res);
                for ($i = 0, $cnt = count($images); $i < $cnt; $i++) {
                    $productItem['file' . ($i + 1)] = $images[$i];
                }
                // $currentImportResult['images'] = $images;
                // // $productItem['Images'] = $images;
                // var_dump($productItem);
                // return;
            }

            // var_dump("[[[[[[[[[[[[[[ inpuda data >>>>>>>>>>>>>>>>>>>>>>>>>>>>");
            // var_dump($productItem);
            // $currentImportResult[] = "[INFO] " . "saving product";
            // echo "[INFO] " . "saving product" . PHP_EOL;
            $currentImportResult['product'] = $productItem;
            $res = API::getAPI('shop:products')->updateOrInsertProduct($productItem);
            $currentImportResult[] = $res['ExternalKey'];
            // var_dump("***************** result *****************");
            // var_dump($res);
            if ($res['created']) {
                //-- echo "[INFO] new product created" . PHP_EOL;
                $currentImportResult[] = "NEW";
                $addedCount++;
            } elseif ($res['updated']) {
                //-- echo "[INFO] updating existent product " . $res['ID'] . PHP_EOL;
                $currentImportResult[] = "UPDATED BY ID " . $res['ID'];
                $updatedCount++;
            } else {
                $errorCount++;
            }
            if (!empty($res['errors'])) {
                $currentImportResult[] = "FAILED:";
                $currentImportResult[] = print_r($res['errors'], true);
                //-- echo "[FAILED] " . PHP_EOL;
                // var_dump($res['errors']);
                ob_flush();
                flush();
            }
            if ($res['success']) {
                $currentImportResult[] = "SUCCESS";
                //-- echo "[SUCCESS] " . PHP_EOL;
                ob_flush();
                flush();
            } else {
                $currentImportResult[] = "ERROR";
                //-- echo "[ERROR] " . $rawProductData['Name'] . PHP_EOL;
                ob_flush();
                flush();
            }
            if (count($res['errors'])) {
                $productError = array();
                $productError[$productItem['Name']] = $res['errors'];
                $errors += $productError;
            }
            // $parsedProducts[] = $productItem;
            // if (count($parsedProducts) > 1) {
            //     break;
            // }
            $processed++;
            //-- echo "[INFO] " . "parsed products count " . $processed . " of " . $total . PHP_EOL;
            set_time_limit(60);
            // var_dump("********************************************");
            $results[$rawProductData['Name'] . ' ' . $rawProductData['Model']] = $currentImportResult;
        }

        // disable all products
        // API::getAPI('shop:products')->archiveAllProducts();

        // var_dump($errors);
        $res = array(
            // 'parsedProducts' => $parsedProducts,
            'TOTAL' => count($parsedProducts),
            'ADDED' => $addedCount,
            'UPDATED' => $updatedCount,
            'ERROR_COUNT' => $errorCount,
            'STATUS' => empty($errors) ? 'OK' : 'NO'
            // 'errors' => $errors,
            // 'I' => implode(';', $results)
        );

        // var_dump($task);
        // API::getAPI('system:tasks')->setTaskResult($task['ID'], utf8_encode(json_encode($results)));
        // var_dump($res);
        API::getAPI('system:tasks')->setTaskResult($task['ID'], print_r($res, true));

        // save log file
        $results['ERRORS'] = $errors;
        $results['INFO'] = $info;
        file_put_contents($feedLogPath, print_r($results, true));

        ob_end_flush();
        // if (ob_get_length()) ob_end_clean();
        // ob_end_clean();
        // if (ob_get_level() == 0) ob_start();
        // echo '<pr>';
        // var_dump($namedDataArray);
        // echo '</pre><hr />';
        // var_dump($objPHPExcel);
        // var_dump($feedPath);
        // $urls = array();
        // $urls[] = 'http://upload.wikimedia.org/wikipedia/commons/6/66/Android_robot.png';
        // $urls[] = 'http://www.notebookcheck.net/uploads/tx_nbc2/delXPS14.jpg';
        // $options = array(
        //     'script_url' =>  API::getAPI('system:tasks')->getConfiguration()->urls->upload,
        //     'download_via_php' => true,
        //     'web_import_temp_dir' => Path::getAppTemporaryDirectory(),
        //     'upload_dir' => Path::getUploadTemporaryDirectory(),
        //     'print_response' => $_SERVER['REQUEST_METHOD'] === 'GET'
        // );
        // $upload_handler = new JqUploadLib($options, false);
        // $res = $upload_handler->importFromUrl($urls, false);
        // return $res;
    }

    public function generateProductFeed () {
        global $app;
        $customer = API::getApi('system:customers')->getRuntimeCustomer();
        $options = array('limit' => 0);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("MPWS Framework")
            ->setLastModifiedBy("MPWS Framework")
            ->setTitle("Shop Products Autogenerated Feed")
            ->setSubject("Shop Products")
            ->setDescription("This feed was generated usign MPWS Framework.")
            ->setKeywords("mpws shop products")
            ->setCategory("shop");
        $dataList = API::getAPI('shop:products')->getProducts_List($options, false, false);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Name')
            ->setCellValue('B1', 'Model')
            ->setCellValue('C1', 'CategoryName')
            ->setCellValue('D1', 'OriginName')
            ->setCellValue('E1', 'Price')
            ->setCellValue('F1', 'Status')
            ->setCellValue('G1', 'IsPromo')
            ->setCellValue('H1', 'IsFeatured')
            ->setCellValue('I1', 'IsOffer')
            ->setCellValue('J1', 'WARRANTY')
            ->setCellValue('K1', 'TAGS')
            ->setCellValue('L1', 'Features')
            ->setCellValue('M1', 'Synopsis')
            ->setCellValue('N1', 'Description')
            ->setCellValue('O1', 'Image1')
            ->setCellValue('P1', 'Image2')
            ->setCellValue('Q1', 'Image3')
            ->setCellValue('R1', 'Image4')
            ->setCellValue('S1', 'Image5');
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
        for ($i = 0, $j = 2, $len = count($dataList['items']); $i < $len; $i++, $j++) {
            $images = array();
            $features = array();
            $warranty = '';
            $tags = '';
            // $expire = '';
            // $isbn = '';
            if (!empty($dataList['items'][$i]['Images'])) {
                foreach ($dataList['items'][$i]['Images'] as $value) {
                    $images[] = $customer['Protocol'] . '://' . $customer['HostName'] . $value['normal'];
                }
            }
            if (isset($dataList['items'][$i]['Attributes'])) {
                if (isset($dataList['items'][$i]['Attributes']['TAGS'])) {
                    $tags = $dataList['items'][$i]['Attributes']['TAGS'];
                }
                if (isset($dataList['items'][$i]['Attributes']['WARRANTY'])) {
                    $warranty = $dataList['items'][$i]['Attributes']['WARRANTY'];
                }
                // if (isset($dataList['items'][$i]['Attributes']['EXPIRE'])) {
                //     $expire = $dataList['items'][$i]['Attributes']['EXPIRE'];
                // }
                // if (isset($dataList['items'][$i]['Attributes']['ISBN'])) {
                //     $isbn = $dataList['items'][$i]['Attributes']['ISBN'];
                // }
            }
            if (isset($dataList['items'][$i]['Features'])) {
                foreach ($dataList['items'][$i]['Features'] as $featureGroupName => $featureGroupItems) {
                    $features[] = $featureGroupName . '=' . join(',', array_values($featureGroupItems));
                }
            }
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $j, $dataList['items'][$i]['Name']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $j, $dataList['items'][$i]['Model']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $j, $dataList['items'][$i]['_category']['Name']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $j, $dataList['items'][$i]['_origin']['Name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $dataList['items'][$i]['_prices']['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $dataList['items'][$i]['Status']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $dataList['items'][$i]['IsPromo'] ? '+' : '');
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $dataList['items'][$i]['IsFeatured'] ? '+' : '');
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $dataList['items'][$i]['IsOffer'] ? '+' : '');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $j, $warranty);//$dataList['items'][$i]['Features']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tags);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, implode('|', $features));//$dataList['items'][$i]['Features']);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $j, $dataList['items'][$i]['Synopsis']);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $j, $dataList['items'][$i]['Description']);
            if (isset($images[0]))
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $j, $images[0]);
            if (isset($images[1]))
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $j, $images[1]);
            if (isset($images[2]))
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $j, $images[2]);
            if (isset($images[3]))
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $j, $images[3]);
            if (isset($images[4]))
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $j, $images[4]);

            // add dropdown to status field
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('F' . $j)->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Input error');
            $objValidation->setError('Value is not in list.');
            $objValidation->setPromptTitle('Pick from list');
            $objValidation->setPrompt('Please pick a value from the drop-down list.');
            $objValidation->setFormula1('"' . join(',', API::getAPI('shop:products')->getProductStatuses()) . '"'); //note this!
        }
        // foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) { $rd->setRowHeight(-1); }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getStyle('J2:S' . (1 + count($dataList['items'])))->getAlignment()->setWrapText(true);
        // $objPHPExcel->getActiveSheet()->getStyle('A2:S' . (1 + count($dataList['items'])))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2:S' . (1 + count($dataList['items'])))->getBorders()->applyFromArray(
             array(
                 'bottom' => array(
                     'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                     'color' => array(
                         'rgb' => '808080'
                     )
                 )
             )
        );
        if (!file_exists(Path::rootPath() . $this->getFeedsUploadDir())) {
            mkdir(Path::rootPath() . $this->getFeedsUploadDir(), 0777, true);
        }
        $fileName = Path::rootPath() . $this->getFeedsUploadDir() . $this->getGeneratedFeedName() . '.xls';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        PHPExcel_Shared_File::setUseUploadTempDirectory(true);
        $objWriter->save($fileName);
        // chmod($fileName, 0644);
        // return $dataList;
    }

    public function get (&$resp, $req) {
        // $res = array('gdfgfdgdggd');
        // echo gzcompress(print_r($res, true)) . PHP_EOL . PHP_EOL;
        // echo mysql_real_escape_string(gzcompress(print_r($res, true))) . PHP_EOL . PHP_EOL;
        if (isset($req->get['generate'])) {
            $resp = $this->generateProductFeed();
        } else {
            $resp = $this->getFeeds();
        }
    }

    public function post (&$resp, $req) {
        if (isset($req->data['name'])) {
            // var_dump($req->data['name']);
            // foreach ($resp['files'] as $tempFileItem) {
                $res = Path::moveTemporaryFile($req->data['name'], $this->getFeedsUploadInnerDir(), $this->getUploadedFeedName());
                API::getAPI('system:tasks')->addTask('shop', 'importProductFeed', $res['basename']);
                // $this->getPlugin()->saveOwnTemporaryUploadedFile(, , );
                // var_dump($res);
                $resp = $this->__adjustFeedItem(Path::rootPath() . $this->getFeedsUploadDir() . $res['filename']);
            // }
        }
    }
    public function patch (&$resp, $req) {
        $activeTasks = API::getAPI('system:tasks')->getActiveTasksByGroupName('shop');
        if (isset($req->data['schedule']) && isset($req->get['name'])) {
            $task = API::getAPI('system:tasks')->isTaskAdded('shop', 'importProductFeed', $req->get['name']);
            if (!empty($task)) {
                if (!$task['Scheduled']) {
                    // API::getAPI('system:tasks')->scheduleTask('shop', 'importProductFeed', $req->get['name']);
                    // this part must be moved into separated process >>>>
                    API::getAPI('system:tasks')->startTask('shop', 'importProductFeed', $req->get['name']);
                    // temporary solution to output json results
                    //-- echo "{ dump: '";
                    $this->importProductFeed($req->get['name']);
                    //-- echo "',";
                    $resp = $this->getFeeds();
                    // <<<< this part must be moved into separated process
                    $resp['success'] = true;
                } else {
                    $this->getFeeds();
                    $resp['error'] = 'CanNotBeStarted';
                }
            } else {
                $this->getFeeds();
                $resp['error'] = 'UnknownTask';
            }
        } else if (isset($req->data['cancel']) && isset($req->get['name'])) {
            $task = API::getAPI('system:tasks')->isTaskAdded('shop', 'importProductFeed', $req->get['name']);
            if (isset($task['Hash'])) {
                API::getAPI('system:tasks')->cancelTask($task['ID']);
                $this->getFeeds();
                $resp['success'] = true;
            } else {
                $this->getFeeds();
                $resp['error'] = 'UnknownTask';
            }
        } else if (count($activeTasks)) {
            $this->getFeeds();
            $resp['error'] = 'ActiveImportFound';
        }
    }

    public function delete (&$resp, $req) {
        $success = false;
        if (isset($req->get['name'])) {
            $task = API::getAPI('system:tasks')->isTaskAdded('shop', 'importProductFeed', $req->get['name']);
            if (isset($taks) && $task['IsRunning']) {
                $resp['error'] = 'UnableToRemoveActiveTask';
            } else {
                $feedPath = Path::rootPath() . $this->getFeedFilePathByName($req->get['name']);
                if (file_exists($feedPath)) {
                    $success = unlink($feedPath);
                    if (isset($task['Hash']) && $success)
                        API::getAPI('system:tasks')->deleteTaskByHash($task['Hash']);
                }
            }
        }
        $resp['success'] = $success;
    }
}

?>