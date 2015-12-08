<?php
namespace static_\plugins\system\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class stats extends API {
    public function get ($req, $resp) {
        $self = $this;
        $sources = array();

        $sources['overview'] = function ($req) use ($self) {
            return array(
                'overview_users' => API::getAPI('system:users')->getStats_UsersOverview(),
                'users_intensity_last_month_active' => API::getAPI('system:users')->getStats_UsersIntensityLastMonth('ACTIVE'),
                'users_intensity_last_month_temp' => API::getAPI('system:users')->getStats_UsersIntensityLastMonth('TEMP'),
                'users_intensity_last_month_removed' => API::getAPI('system:users')->getStats_UsersIntensityLastMonth('REMOVED')
            );
        };
        $type = false;
        if (!empty($req->get['type']))
            $type = $req->get['type'];

        if (isset($sources[$type]))
            $resp->setResponse($sources[$type]($req));
        else
            $resp->setError('WrongType');
    }
}

?>