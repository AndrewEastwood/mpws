<?php
namespace web\plugin\shop\api;

use \engine\lib\uploadHandler as JqUploadLib;
use \engine\lib\path as Path;
use PHPExcel as PHPExcel;
use PHPExcel_IOFactory as PHPExcel_IOFactory;

class feeds extends \engine\objects\api {

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
        return $this->getFeedsUploadInnerDir() . $name;
    }

    public function getFeedsUploadInnerDir () {
        $path = Path::createDirPath('shop', $this->getDirNameFeeds());
        return Path::getUploadDirectory($path);
    }

    public function getFeedFilePathByName ($feedName) {
        return $this->getFeedsUploadInnerDir() . $feedName . '.xls';
    }

    public function getGeneratedFeedsFilesList () {
        return glob($this->getFeedsUploadInnerDir() . 'gen_*\.xls');
    }

    public function getUploadedFeedsFilesList () {
        return glob($this->getFeedsUploadInnerDir() . 'import_*\.xls');
    }

    public function getFeeds () {
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
                unlink($listFeedsUploaded[0]);
            }
            $attempts--;
        } while (count($listFeedsUploaded) > 10 && $attempts > 0);

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
        foreach ($listFeedsUploaded as $value) {
            $pInfo = pathinfo($value);
            $ftime = filectime($value);
            $feeds[] = array(
                'ID' => md5($pInfo['filename']),
                'type' => 'uploaded',
                'time' => $ftime,
                'timeFormatted' => date('Y-m-d H:i:s', $ftime),
                'name' => $pInfo['filename'],
                'link' => $this->getGeneratedFeedDownloadLink($pInfo['basename'])
            );
        }

        return $feeds;
    }

    public function importProductFeed () {
        // $urls = array();
        // $urls[] = 'http://upload.wikimedia.org/wikipedia/commons/6/66/Android_robot.png';
        // $urls[] = 'http://www.notebookcheck.net/uploads/tx_nbc2/delXPS14.jpg';
        // $options = array(
        //     'script_url' =>  $this->getCustomer()->getConfiguration()->urls->upload,
        //     'download_via_php' => true,
        //     'web_import_temp_dir' => Path::getAppTemporaryDirectory(),
        //     'upload_dir' => Path::getUploadTemporaryDirectory(),
        //     'print_response' => $_SERVER['REQUEST_METHOD'] === 'GET'
        // );
        // $upload_handler = new JqUploadLib($options, false);
        // $rez = $upload_handler->importFromUrl($urls, false);
        return $rez;
    }

    public function generateProductFeed () {
        $options = array('limit' => 0);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("PHPExcel Test Document")
            ->setSubject("PHPExcel Test Document")
            ->setDescription("Test document for PHPExcel, generated using PHP classes.")
            ->setKeywords("office PHPExcel php")
            ->setCategory("Test result file");
        $dataList = $this->getAPI()->products->getProducts_List($options, false, false);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Name')
            ->setCellValue('B1', 'Model')
            ->setCellValue('C1', 'CategoryName')
            ->setCellValue('D1', 'OriginName')
            ->setCellValue('E1', 'Price')
            ->setCellValue('F1', 'Status')
            ->setCellValue('G1', 'IsPromo')
            ->setCellValue('H1', 'Images')
            ->setCellValue('I1', 'TAGS')
            ->setCellValue('J1', 'Description')
            ->setCellValue('K1', 'Features');
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
        for ($i = 0, $j = 2, $len = count($dataList['items']); $i < $len; $i++, $j++) {
            $images = array();
            $features = array();
            $tags = '';
            // $expire = '';
            // $isbn = '';
            if (!empty($dataList['items'][$i]['Images'])) {
                foreach ($dataList['items'][$i]['Images'] as $value) {
                    $images[] = 'http://' . $_SERVER['HTTP_HOST'] . $value['normal'];
                }
            }
            if (isset($dataList['items'][$i]['Attributes'])) {
                if (isset($dataList['items'][$i]['Attributes']['TAGS'])) {
                    $tags = $dataList['items'][$i]['Attributes']['TAGS'];
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
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $dataList['items'][$i]['Name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $dataList['items'][$i]['Model']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $dataList['items'][$i]['CategoryName']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $dataList['items'][$i]['OriginName']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $dataList['items'][$i]['Price']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $dataList['items'][$i]['Status']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $dataList['items'][$i]['IsPromo']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, implode(PHP_EOL, $images));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tags);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $dataList['items'][$i]['Description']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, implode('|', $features));//$dataList['items'][$i]['Features']);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($this->getFeedsUploadInnerDir() . $this->getGeneratedFeedName() . '.xls');
        // return $dataList;
    }

    public function get (&$resp, $req) {
        $resp = $this->getFeeds();
    }

    public function post (&$resp, $req) {
        if (isset($req->get['generate'])) {
            $resp = $this->generateProductFeed();
        } elseif (isset($resp['files'])) {
            foreach ($resp['files'] as $tempFileItem) {
                Path::moveTemporaryFile($tempFileItem->name, $this->getFeedsUploadInnerDir(), $this->getUploadedFeedName());
                // $this->getPlugin()->saveOwnTemporaryUploadedFile(, , );
            }
        }
    }
    public function patch (&$resp, $req) {
        if (isset($req->data['import']) && isset($req->get['name'])) {
            $resp = $this->importProductFeed($req->get['name']);
        }
    }

    public function delete (&$resp, $req) {
        $success = false;
        if (isset($req->get['name'])) {
            $feedPath = $this->getFeedFilePathByName($req->get['name']);
            if (file_exists($feedPath)) {
                $success = unlink($feedPath);
            }
        }
        $resp['success'] = $success;
    }
}

?>