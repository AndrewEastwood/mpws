<?php

class apiShopFeeds extends objectApi {


    public function importProductFeed () {

    }

    public function exportProductFeed () {

    }


    public function get (&$resp, $req) {
        $type = $req->get['type'] ?: false
        if ($type === 'products') {
            
        }
    }
}

?>