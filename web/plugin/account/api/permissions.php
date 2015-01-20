<?php
namespace web\plugin\account\api;

class permissions {

    public static function getNewPermissions () {
        global $app;
        $perms = array(
            "CanAdmin" => 0,
            "CanCreate" => 0,
            "CanEdit" => 0,
            "CanViewReports" => 0,
            "CanAddUsers" => 0
        );
        return $perms;
    }

    public function getPermissions ($userID) {
        global $app;
        $query = dbquery::getPermissions($userID);
        $userPermissions = $app->getDB()->query($query);
        return $this->_adjustPermissions($userPermissions);
    }

    public function createPermissions ($userID, $permissions = array()) {
        global $app;
        $perms = array_merge(self::getNewPermissions(), $permissions);
        $query = dbquery::addPermissions($perms);
        $PermissionID = $app->getDB()->query($query) ?: null;
        return $PermissionID;
    }

    public function updatePermissions ($userID, $permissions = array()) {
        global $app;
        $perms = array_merge(self::getNewPermissions(), $permissions);
        $query = dbquery::addPermissions($perms);
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