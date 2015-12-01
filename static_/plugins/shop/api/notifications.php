<?php
namespace static_\plugins\shop\notifications;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use \engine\lib\utils as Utils;
use \engine\lib\email as Email;
use Exception;
use ArrayObject;
use Mandrill as Mandrill;

class notifications extends API {


    public function notify ($type, $data) {
        switch ($type) {
            case 'orderCreated':
                break;
            case 'orderUpdated':
                break;
            case 'orderClosed':
                break;
            case 'productPriceGoDown':
                break;
            case 'productIsAvailableNow':
                break;
            case 'categoryCreated':
                break;
            case 'originCreated':
                break;
        }
    }

    // TODO: 
    //   1. implement in the system api file that grabs all sybscribers by type
    //   2. create functions according to the shop's notifications
    //   3. invoke them in proper places

}