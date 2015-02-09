<?php
namespace web\plugin\system\api;

class permissions {

    public static function getNewPermissions (array $customPermissions = array()) {
        $perms = array(
            "CanAdmin" => 0,
            "CanCreate" => 0,
            "CanEdit" => 0,
            "CanViewReports" => 0,
            "CanAddUsers" => 0,
            "CanUpload" => 0,
            "CanManage" => 0
        );
        return array_merge($perms, $customPermissions);
    }

    public function getPermissions ($userID) {
        global $app;
        $query = dbquery::getPermissions($userID);
        $userPermissions = $app->getDB()->query($query, false);
        return $this->_adjustPermissions($userPermissions);
    }

    public function createPermissions ($userID, $permissions = array()) {
        global $app;
        $perms = self::getNewPermissions($permissions);
        $perms['UserID'] = $userID;
        $query = dbquery::createPermissions($perms);
        $PermissionID = $app->getDB()->query($query) ?: null;
        return $PermissionID;
    }

    public function updatePermissions ($userID, $permissions = array()) {
        global $app;
        $perms = self::getNewPermissions($permissions);
        $query = dbquery::updatePermissions($userID, $perms);
        $PermissionID = $app->getDB()->query($query) ?: null;
        return $PermissionID;
    }

    private function _adjustPermissions ($perms) {
        $adjustedPerms = array();
        // adjust permission values
        foreach ($perms as $field => $value) {
            if (preg_match("/^Can/", $field) === 1)
                $adjustedPerms[$field] = intval($value) === 1;
        }
        // $this->permissions = $listOfDOs;
        return $adjustedPerms;
    }

}

?>