<?php

class apiShopFeeds extends objectApi {

    public function getPathFeeds () {
        return 'feeds';
    }

    public function getGeneratedFeedDownloadLink ($name) {
        return $this->getPlugin()->getOwnUploadedFileWeb($name, $this->getPathFeeds());
    }

    public function getGeneratedFeedsPaths () {
        return glob($this->getPlugin()->getOwnUploadDirectory($this->getPathFeeds()) . DS . 'gen_*\.xls');
    }

    public function getUploadedFeedsPaths () {
        return glob(libraryUtils::getUploadTemporaryDirectory() . DS . '*.xls');
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

        return $feeds;
    }

    public function importProductFeed () {

    }

    public function generateProductFeed () {

    }

    public function get (&$resp, $req) {
        $resp['feeds'] = $this->getFeeds();
    }
}

?>