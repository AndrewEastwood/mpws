<?php

class apiShopFeeds extends objectApi {

    public function getDirNameFeeds () {
        return 'feeds';
    }

    public function getUploadedFeedName () {
        return 'import_' . date('YmdHis');
    }

    public function getGeneratedFeedDownloadLink ($name) {
        return $this->getPlugin()->getOwnUploadedFileWeb($name, $this->getDirNameFeeds());
    }

    public function getGeneratedFeedsPaths () {
        return glob($this->getPlugin()->getOwnUploadDirectory($this->getDirNameFeeds()) . DS . 'gen_*\.xls');
    }

    public function getUploadedFeedsPaths () {
        return glob($this->getPlugin()->getOwnUploadDirectory($this->getDirNameFeeds()) . DS . 'import_*\.xls');
    }

    public function getFeeds () {
        $listFeedsGenerated = $this->getGeneratedFeedsPaths();
        $listFeedsUploaded = $this->getUploadedFeedsPaths();
        $feeds = array();

        foreach ($listFeedsGenerated as $value) {
            $pInfo = pathinfo($value);
            $feeds[] = array(
                'ID' => md5($pInfo['filename']),
                'type' => 'generated',
                'time' => date('Y-m-d H:i:s', filectime($value)),
                'name' => $pInfo['filename'],
                'link' => $this->getGeneratedFeedDownloadLink($pInfo['basename'])
            );
        }
        foreach ($listFeedsUploaded as $value) {
            $pInfo = pathinfo($value);
            $feeds[] = array(
                'ID' => md5($pInfo['filename']),
                'type' => 'uploaded',
                'time' => date('Y-m-d H:i:s', filectime($value)),
                'name' => $pInfo['filename'],
                'link' => $this->getGeneratedFeedDownloadLink($pInfo['basename'])
            );
        }

        return $feeds;
    }

    public function importProductFeed () {

    }

    public function generateProductFeed () {

    }

    public function get (&$resp, $req) {
        $resp['feeds'] = $this->getFeeds();
    }

    public function post (&$resp, $req) {
        if (isset($resp['files'])) {
            foreach ($resp['files'] as $tempFileItem) {
                $this->getPlugin()->saveOwnTemporaryUploadedFile($tempFileItem->name, $this->getDirNameFeeds(), $this->getUploadedFeedName());
            }
        }
    }
}

?>