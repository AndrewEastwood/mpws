<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class settings {

    public function __construct () {
        $types = array_keys(dbquery::$SETTING_TYPE_TO_DBTABLE_MAP);
        $this->SETTING_TYPE_ARRAY = $types;
        $this->SETTING_TYPE = (object)array_combine($types, $types);
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // SETTINGS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getSettingsAddress ($addressID) {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByID($this->SETTING_TYPE->ADDRESS, $addressID));
        if (empty($item)) {
            return null;
        }
        $item = $this->__adjustSettingItem($item);
        return $item;
    }
    public function getSettingsAddresses () {
        global $app;
        $config = null;
        if ($app->isToolbox()) {
            $config = dbquery::shopGetSettingByType($this->SETTING_TYPE->ADDRESS);
        } else {
            $config = dbquery::shopGetSettingsAddressActive();
        }
        $items = $app->getDB()->query($config) ?: array();
        foreach ($items as &$value) {
            $value = $this->__adjustSettingItem($this->SETTING_TYPE->ADDRESS, $value);
        }
        return $items;
    }
    public function getSettingsAddressPhones ($addressID) {
        global $app;
        // phones
        $config = dbquery::shopGetSettingsAddressPhones($addressID);
        $items = $app->getDB()->query($config) ?: array();
        foreach ($items as &$value)
            $value = $this->__adjustSettingItem($this->SETTING_TYPE->PHONES, $value);
        return $items;
    }
    public function getSettingsAddressOpenHours ($addressID) {
        global $app;
        // open hours
        $config = dbquery::shopGetSettingsAddressOpenHours($addressID);
        $item = $app->getDB()->query($config) ?: array();
        return $this->__adjustSettingItem($this->SETTING_TYPE->OPENHOURS, $item);
    }
    public function getSettingsAddressInfo ($addressID) {
        global $app;
        $config = dbquery::shopGetSettingsAddressInfo($addressID);
        $item = $app->getDB()->query($config) ?: array();
        return $this->__adjustSettingItem($this->SETTING_TYPE->INFO, $item);
    }
    public function getSettingsAlerts () {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->ALERTS));
        $this->__adjustSettingItem($this->SETTING_TYPE->ALERTS, $item);
        if (empty($item))
            return $item;
        return $item;
    }
    public function getSettingsExchangeRates () {}
    public function getSettingsExchangeRatesDisplay () {
        global $app;
        $items = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->EXCHANAGERATESDISPLAY)) ?: array();
        foreach ($items as &$value) {
            if (empty($value['CurrencyName'])) {
                $rez = $this->removeSetting($this->SETTING_TYPE->EXCHANAGERATESDISPLAY, $value['ID']);
                unset($value);
                continue;
            }
            $value = $this->__adjustSettingItem($this->SETTING_TYPE->EXCHANAGERATESDISPLAY, $value);
        }
        return $items;
    }
    public function getSettingsMisc () {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->MISC));
        return $this->__adjustSettingItem($this->SETTING_TYPE->MISC, $item) ?: array();
    }
    public function getSettingsProduct () {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->PRODUCT));
        $this->__adjustSettingItem($this->SETTING_TYPE->PRODUCT, $item) ?: array();
        if (empty($item))
            return $item;
        return $item;
    }
    public function getSettingsSeo () {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->SEO));
        return $this->__adjustSettingItem($this->SETTING_TYPE->SEO, $item) ?: array();
    }
    public function getSettingsFormOrder () {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->FORMORDER));
        $this->__adjustSettingItem($this->SETTING_TYPE->FORMORDER, $item) ?: array();
        if (empty($item))
            return $item;
        return $item;
    }
    public function getSettingsWebsite () {
        global $app;
        $item = $app->getDB()->query(dbquery::shopGetSettingByType($this->SETTING_TYPE->WEBSITE));
        return $this->__adjustSettingItem($this->SETTING_TYPE->WEBSITE, $item) ?: array();
    }
    public function getSettingByID ($type, $id) {
        global $app;
        $item = null;
        try {
            $item = $app->getDB()->query(dbquery::shopGetSettingByID($type, $id));
        } catch (Exception $ex) { }
        return $this->__adjustSettingItem($type, $item);
    }
    public function getSettings () {
        $settings = array();
        $settings[$this->SETTING_TYPE->ADDRESS] = $this->getSettingsAddresses();
        $settings[$this->SETTING_TYPE->ALERTS] = $this->getSettingsAlerts();
        // $settings[$this->SETTING_TYPE->INFO] = $this->getSettingsAddressInfo();
        $settings[$this->SETTING_TYPE->MISC] = $this->getSettingsMisc();
        $settings[$this->SETTING_TYPE->PRODUCT] = $this->getSettingsProduct();
        $settings[$this->SETTING_TYPE->SEO] = $this->getSettingsSeo();
        $settings[$this->SETTING_TYPE->WEBSITE] = $this->getSettingsWebsite();
        $settings[$this->SETTING_TYPE->FORMORDER] = $this->getSettingsFormOrder();
        $settings[$this->SETTING_TYPE->EXCHANAGERATESDISPLAY] = $this->getSettingsExchangeRatesDisplay();
        $settings[$this->SETTING_TYPE->EXCHANAGERATES] = array(
            'availableConversions' => API::getAPI('shop:exchangerates')->getAvailableConversionOptions(),
            'availableMutipliers' => API::getAPI('shop:exchangerates')->getActiveRateMultipliers()
       );
        return $settings;
    }

    private function __adjustSettingItem ($type, &$setting) {
        global $app;
        if (empty($setting)) {
            return null;
        }
        if (isset($setting['ID'])) {
            $setting['ID'] = intval($setting['ID']);
        }
        if (isset($setting['ShopAddressID'])) {
            $setting['ShopAddressID'] = intval($setting['ShopAddressID']);
        }
        if (isset($setting['CustomerID'])) {
            unset($setting['CustomerID']);
        }
        switch ($type) {
            case 'SEO':
                break;
            case 'ALERTS':
                $setting["AllowAlerts"] = intval($setting["AllowAlerts"]) === 1;
                $setting["NewProductAdded"] = intval($setting["NewProductAdded"]) === 1;
                $setting["ProductPriceGoesDown"] = intval($setting["ProductPriceGoesDown"]) === 1;
                $setting["PromoIsStarted"] = intval($setting["PromoIsStarted"]) === 1;
                $setting["AddedNewOrigin"] = intval($setting["AddedNewOrigin"]) === 1;
                $setting["AddedNewCategory"] = intval($setting["AddedNewCategory"]) === 1;
                $setting["AddedNewDiscountedProduct"] = intval($setting["AddedNewDiscountedProduct"]) === 1;
                break;
            case 'ADDRESS':
                $setting['_isActive'] = $setting['Status'] === 'ACTIVE';
                break;
            case 'MISC':
                $setting['ShowSiteCurrencySelector'] = intval($setting['ShowSiteCurrencySelector']) === 1;
                break;
            case 'WEBSITE':
                $setting['DeliveryAllowSelfPickup'] = intval($setting['DeliveryAllowSelfPickup']) === 1;
                break;
            case 'FORMORDER':
                $setting["ShowName"] = intval($setting["ShowName"]) === 1;
                $setting["ShowEMail"] = intval($setting["ShowEMail"]) === 1;
                $setting["ShowPhone"] = intval($setting["ShowPhone"]) === 1;
                $setting["ShowAddress"] = intval($setting["ShowAddress"]) === 1;
                $setting["ShowPOBox"] = intval($setting["ShowPOBox"]) === 1;
                $setting["ShowCountry"] = intval($setting["ShowCountry"]) === 1;
                $setting["ShowCity"] = intval($setting["ShowCity"]) === 1;
                $setting["ShowDeliveryAganet"] = intval($setting["ShowDeliveryAganet"]) === 1;
                $setting["ShowComment"] = intval($setting["ShowComment"]) === 1;
                $setting["SucessTextLines"] = $setting["SucessTextLines"];
                $setting["ShowOrderTrackingLink"] = intval($setting["ShowOrderTrackingLink"]) === 1;
                break;
            case 'PRODUCT':
                $setting["ShowOpenHours"] = intval($setting["ShowOpenHours"]) === 1;
                $setting["ShowDeliveryInfo"] = intval($setting["ShowDeliveryInfo"]) === 1;
                $setting["ShowPaymentInfo"] = intval($setting["ShowPaymentInfo"]) === 1;
                $setting["ShowSocialSharing"] = intval($setting["ShowSocialSharing"]) === 1;
                $setting["ShowPriceChart"] = intval($setting["ShowPriceChart"]) === 1;
                $setting["ShowWarrantyInfo"] = intval($setting["ShowWarrantyInfo"]) === 1;
                $setting["ShowContacts"] = intval($setting["ShowContacts"]) === 1;
                break;
            // case 'PHONES':
            //     break;
            // case 'OPENHOURS':
            //     break;
            case 'EXCHANAGERATESDISPLAY':
                // $setting["CurrencyID"] = intval($setting["CurrencyID"]);
                // $setting["ShowSignBeforeValue"] = intval($setting["ShowSignBeforeValue"]) === 1;
                break;
            default:
                break;
        }
        return $setting;
    }

    public function createOrUpdateSetting ($type, $reqData, $settingID = null) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $isUpdate = $settingID !== null;

        try {

            $type = dbquery::getVerifiedSettingsType($type);
            if (empty($type)) {
                throw new Exception("WrongSettingsType", 1);
            }

            $count = $this->getCustomerSettingsCount($type);
            $mustBeSingle = dbquery::isOneForCustomer($type);

            if ($mustBeSingle && $count > 0 && !$isUpdate) {
                throw new Exception("PropertyAlreadyExistsUsePatchMethod", 1);
            }

            $validatedDataObj = array();
            switch ($type) {
                case 'SEO':
                    $dataRules = array(
                        'ProductKeywords' => array('string', 'skipIfUnset'),
                        'CategoryKeywords' => array('string', 'skipIfUnset'),
                        'HomePageKeywords' => array('string', 'skipIfUnset'),
                        'ProductDescription' => array('string', 'skipIfUnset'),
                        'CategoryDescription' => array('string', 'skipIfUnset'),
                        'HomePageDescription' => array('string', 'skipIfUnset'),
                        'ProductPageTitle' => array('string', 'skipIfUnset'),
                        'CategoryPageTitle' => array('string', 'skipIfUnset'),
                        'HomePageTitle' => array('string', 'skipIfUnset')
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'ALERTS':
                    $dataRules = array(
                        'AllowAlerts' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'NewProductAdded' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ProductPriceGoesDown' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'PromoIsStarted' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'AddedNewOrigin' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'AddedNewCategory' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'AddedNewDiscountedProduct' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'PRODUCT':
                    $dataRules = array(
                        'ShowOpenHours' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowDeliveryInfo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPaymentInfo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowSocialSharing' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPriceChart' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowWarrantyInfo' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowContacts' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'ADDRESS':
                    if (!$isUpdate && $count >= 3) {
                        throw new Exception("AddressLimitReached", 1);
                    }
                    $dataRules = array(
                        'ShopName' => array('string', 'defaultValueIfUnset' => 'noname shop'),
                        'Country' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'City' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'AddressLine1' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'AddressLine2' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'AddressLine3' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'MapUrl' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'SocialFacebook' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'SocialTwitter' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'SocialLinkedIn' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'SocialGooglePlus' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'PhoneHotline' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone1Label' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone1Value' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone2Label' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone2Value' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone3Label' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone3Value' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone4Label' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone4Value' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone5Label' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'Phone5Value' => array('skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursMonday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursTuesday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursWednesday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursThursday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursFriday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursSaturday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'HoursSunday' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'InfoPayment' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'InfoShipping' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'InfoWarranty' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'EmailSupport' => array('isEmail', 'skipIfEmpty'),
                        'Status' => array('string', 'skipIfUnset')
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    // var_dump($validatedDataObj['values']);
                    // $dataRules = $this->getSettingsAddresses();
                    break;
                case 'MISC':
                    $dataRules = array(
                        'DBPriceCurrencyType' => array('string'),
                        'SiteDefaultPriceCurrencyType' => array('string'),
                        'ShowSiteCurrencySelector' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                // case 'PHONES':
                //     $dataRules = array(
                //         'ShopAddressID' => array('int'),
                //         'Label' => array('string'),
                //         'Value' => array('string')
                //     );
                //     $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                //     if (isset($validatedDataObj['values']['ShopAddressID'])) {
                //         $phones = $this->getSettingsAddressPhones($validatedDataObj['values']['ShopAddressID']);
                //         if (count($phones) >= 10 && !$isUpdate) {
                //             throw new Exception("PhonesLimitReached", 1);
                //         }
                //     }
                //     if ($isUpdate) {
                //         unset($validatedDataObj['values']['ShopAddressID']);
                //     }
                //     break;
                // case 'OPENHOURS':
                //     $dataRules = array(
                //         'ShopAddressID' => array('int')
                //     );
                //     $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                //     if (isset($validatedDataObj['values']['ShopAddressID'])) {
                //         $ophrs = $this->getSettingsAddressOpenHours($validatedDataObj['values']['ShopAddressID']);
                //         if (!empty($ophrs) && !$isUpdate) {
                //             throw new Exception("OnlyOneSchedulePerAddressAllowed", 1);
                //         }
                //     }
                //     if ($isUpdate) {
                //         unset($validatedDataObj['values']['ShopAddressID']);
                //     }
                //     break;
                // case 'INFO':
                //     $dataRules = array(
                //         'ShopAddressID' => array('int'),
                //         'Shipping' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                //         'Payment' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                //         'Warranty' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => '')
                //     );
                //     $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                //     if (isset($validatedDataObj['values']['ShopAddressID'])) {
                //         $info = $this->getSettingsAddressInfo($validatedDataObj['values']['ShopAddressID']);
                //         if (!empty($info) && !$isUpdate) {
                //             throw new Exception("OnlyOneSchedulePerAddressAllowed", 1);
                //         }
                //     }
                //     if ($isUpdate) {
                //         unset($validatedDataObj['values']['ShopAddressID']);
                //     }
                //     break;
                case 'WEBSITE':
                    // $dataRules = $this->getSettingsWebsite();
                    break;
                case 'EXCHANAGERATESDISPLAY':
                    $dataRules = array(
                        'CurrencyName' => array('string'),
                        'Format' => array('string'),
                        'Label' => array('string')
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
                case 'FORMORDER':
                    $dataRules = array(
                        'ShowName' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowEMail' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPhone' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowAddress' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowPOBox' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowCountry' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowCity' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowDeliveryAganet' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'ShowComment' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0),
                        'SucessTextLines' => array('string', 'skipIfUnset', 'defaultValueIfUnset' => ''),
                        'ShowOrderTrackingLink' => array('bool', 'skipIfUnset', 'defaultValueIfUnset' => 0, 'ifTrueSet' => 1, 'ifFalseSet' => 0)
                    );
                    $validatedDataObj = Validate::getValidData($reqData, $dataRules);
                    break;
            }

            // var_dump($validatedDataObj);

            if (!empty($validatedDataObj) && $validatedDataObj["totalErrors"] == 0 && empty($errors)) {
                try {

                    $validatedValues = $validatedDataObj['values'];
                    // var_dump($validatedValues);

                    $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                    $app->getDB()->beginTransaction();

                    if ($isUpdate) {
                        unset($validatedValues['ID']);
                        $config = dbquery::shopUpdateSetting($type, $settingID, $validatedValues);
                        $app->getDB()->query($config);
                    } else {
                        $config = dbquery::shopCreateSetting($type, $validatedValues);
                        $settingID = $app->getDB()->query($config) ?: null;
                    }

                    if (!$isUpdate && empty($settingID)) {
                        throw new Exception('SettingCreateError');
                    }

                    $app->getDB()->commit();

                    $success = true;
                } catch (Exception $e) {
                    $app->getDB()->rollBack();
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors += $validatedDataObj["errors"];
            }

            if ($success && !empty($settingID) || $isUpdate) {
                $result = $this->getSettingByID($type, $settingID);
                if (empty($result)) {
                    throw new Exception("WrongSettingsID", 1);
                }
            }

        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function removeSetting ($type, $settingID) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        try {

            $type = dbquery::getVerifiedSettingsType($type);
            if (empty($type)) {
                throw new Exception("WrongSettingsType", 1);
            }

            $canBeRemoved = dbquery::settingCanBeRemoved($type);

            if (!$canBeRemoved) {
                throw new Exception("SettingIsLockedForRemoval", 1);
            }

            $s = $this->getSettingByID($type, $settingID);
            if (empty($s)) {
                throw new Exception("WrongSettingsID", 1);
            }

            try {
                $app->getDB()->beginTransaction();
                $config = dbquery::shopRemoveSetting($type, $settingID);
                $app->getDB()->query($config);
                $app->getDB()->commit();
                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function getVerifiedSettingsTypeObj ($req) {
        $typeObj = array(
            'error' => false,
            'mustBeSingle' => false,
            'type' => null
        );
        if (!isset($req->get['type'])) {
            $typeObj['error'] = 'MissedParameter_type';
        } else {
            $type = dbquery::getVerifiedSettingsType($req->get['type']);
            $typeObj['type'] = $type;
            if (is_null($type)) {
                $typeObj['error'] = 'WrongSettingsType_' . $req->get['type'];
            } else {
                $typeObj['single'] = dbquery::isOneForCustomer($type);
            }
        }
        return (object)$typeObj;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getCustomerSettingsCount ($type) {
        global $app;
        $config = dbquery::customerSettingsCount($type);
        $sCount = $app->getDB()->query($config);
        if (empty($sCount)) {
            return null;
        }
        return intval($sCount['ItemsCount']);
    }

    public function createDefaultSettingsMisc () {
        $defaultMisc = array();
        $defaultMisc['DBPriceCurrencyType'] = 'USD';
        $defaultMisc['SiteDefaultPriceCurrencyType'] = 'USD';
        return $this->createOrUpdateSetting($this->SETTING_TYPE->MISC, $defaultMisc);
    }

    public function createDefaultSettingsIfNotExist () {
        foreach ($this->SETTING_TYPE_ARRAY as $type) {
            $sqlTableName = dbquery::getSettingsDBTableNameByType($type);
            if (!empty($sqlTableName)) {
                $count = $this->getCustomerSettingsCount($type);
                if ($count === 0) {
                    // var_dump($type, 'sql table name = ',$sqlTableName);
                    if ($type === $this->SETTING_TYPE->MISC)
                        $this->createDefaultSettingsMisc();
                    else
                        $this->createOrUpdateSetting($type, array());
                }
            }
        }
    }
    // public function getSettingsMapFormOrder () {
    //     $map = array();
    //     $items = $this->getSettingsFormOrder();
    //     foreach ($items as $value) {
    //         $map[$value['Property']] = $value;
    //     }
    //     return $map;
    // }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        $this->createDefaultSettingsIfNotExist();
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if (empty($typeObj->type)) {
            $resp = $this->getSettings();
        } else {
            switch ($typeObj->type) {
                case 'SEO':
                    $resp = $this->getSettingsSeo();
                    break;
                case 'ALERTS':
                    $resp = $this->getSettingsAlerts();
                    break;
                case 'ADDRESS':
                    if (isset($req->get['address']) && is_numeric($req->get['address']))
                        $resp = $this->getSettingsAddress($req->get['address']);
                    else
                        $resp = $this->getSettingsAddresses();
                    break;
                // case 'INFO':
                //     if (isset($req->get['address']) && is_numeric($req->get['address']))
                //         $resp = $this->getSettingsAddressInfo($req->get['address']);
                //     else
                //         $resp['error'] = "AddressIsMissing";
                //     break;
                case 'WEBSITE':
                    $resp = $this->getSettingsWebsite();
                    break;
                case 'FORMORDER':
                    $resp = $this->getSettingsFormOrder();
                    break;
                case 'PRODUCT':
                    $resp = $this->getSettingsProduct();
                    break;
                case 'MISC':
                    $resp = $this->getSettingsMisc();
                    break;
                case 'EXCHANAGERATESDISPLAY':
                    $resp = $this->getSettingsExchangeRatesDisplay();
                    break;
                // case 'PHONES':
                //     if (isset($req->get['address']) && is_numeric($req->get['address']))
                //         $resp = $this->getSettingsAddressPhones($req->get['address']);
                //     else
                //         $resp['error'] = "AddressIsMissing";
                //     break;
                // case 'OPENHOURS':
                //     if (isset($req->get['address']) && is_numeric($req->get['address']))
                //         $resp = $this->getSettingsAddressOpenHours($req->get['address']);
                //     else
                //         $resp['error'] = "AddressIsMissing";
                //     break;
                default:
                    $resp['error'] = "WrongSettingsType";
                    break;
            }
        }
    }

    public function post (&$resp, $req) {
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if ($typeObj->error) {
            $resp['error'] = "WrongSettingsType";
        } else {
            $resp = $this->createOrUpdateSetting($typeObj->type, $req->data);
        }
    }

    public function put (&$resp, $req) {
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if ($typeObj->error) {
            $resp['error'] = "WrongSettingsType";
        } else {
            if (isset($req->data['ID']) && is_numeric($req->data['ID'])) {
                $settingID = intval($req->data['ID']);
                $resp = $this->createOrUpdateSetting($typeObj->type, $req->data, $settingID);
            } else {
                $resp['error'] = "WrongSettingsID";
            }
        }
    }

    public function delete (&$resp, $req) {
        // if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
        //     $resp['error'] = "AccessDenied";
        //     return;
        // }
        $typeObj = $this->getVerifiedSettingsTypeObj($req);
        if ($typeObj->error) {
            $resp['error'] = "WrongSettingsType";
        } else {
            if (isset($req->data['ID']) && is_numeric($req->data['ID'])) {
                $settingID = intval($req->data['ID']);
                $resp = $this->removeSetting($typeObj->type, $settingID);
            } else {
                $resp['error'] = "WrongSettingsID";
            }
        }
        // if (empty($req->get['id'])) {
        //     $resp['error'] = 'MissedParameter_id';
        // } elseif (empty($req->get['type'])) {
        //     $resp['error'] = 'MissedParameter_type';
        // } else {
        //     $settingID = intval($req->get['id']);
        //     $resp = $this->remove($req->get['type'], $settingID);
        // }
    }

}

?>