<?php
namespace static_\plugins\shop\api;

class shoputils {

    public static function createProductExternalKey ($data) {
        $ExternalKey = array();
        if (isset($data['OriginName']))
            $ExternalKey[] = $data['OriginName'];
        if (isset($data['Model']))
            $ExternalKey[] = str_replace(' ', '', $data['Model']);
        $ExternalKeyStr = implode(' ', $ExternalKey);
        $ExternalKeyStr = \engine\lib\utils::url_slug($ExternalKeyStr, array('transliterate' => true));
        $ExternalKeyStr = substr($ExternalKeyStr, 0, 600);
        return strtolower($ExternalKeyStr);
    }
}

?>