<?php

class apiShopFeeds extends objectApi {

    public function getDirNameFeeds () {
        return 'feeds';
    }

    public function getUploadedFeedName () {
        return 'import_' . date('YmdHis');
    }

    public function getGeneratedFeedName () {
        return 'gen_' . date('YmdHis');
    }

    public function getGeneratedFeedDownloadLink ($name) {
        return $this->getPlugin()->getOwnUploadedFileWeb($name, $this->getDirNameFeeds());
    }

    public function getFeedsPath () {
        return $this->getPlugin()->getOwnUploadDirectory($this->getDirNameFeeds());
    }

    public function getGeneratedFeedsFilesList () {
        return glob($this->getFeedsPath() . DS . 'gen_*\.xls');
    }

    public function getUploadedFeedsFilesList () {
        return glob($this->getFeedsPath() . DS . 'import_*\.xls');
    }

    public function getFeeds () {
        $listFeedsGenerated = $this->getGeneratedFeedsFilesList();
        $listFeedsUploaded = $this->getUploadedFeedsFilesList();
        $feeds = array();

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
        $dataList = $this->getPlugin()->getProducts_List($options, false, false);
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
        for ($i = 0, $j = 2, $len = count($dataList['items']); $i < $len; $i++, $j++) {
            $images = array();
            if (!empty($dataList['items'][$i]['Images'])) {
                foreach ($dataList['items'][$i]['Images'] as $value) {
                    $images[] = 'http://' . $_SERVER['HTTP_HOST'] . $value['normal'];
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
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, '');//$dataList['items'][$i]['TAGS']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $dataList['items'][$i]['Description']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, '');//$dataList['items'][$i]['Features']);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($this->getFeedsPath() . $this->getGeneratedFeedName() . '.xls');
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
                $this->getPlugin()->saveOwnTemporaryUploadedFile($tempFileItem->name, $this->getDirNameFeeds(), $this->getUploadedFeedName());
            }
        }
    }
}

?>