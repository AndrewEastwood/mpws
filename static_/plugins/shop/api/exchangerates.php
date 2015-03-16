<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;
use curl_init;

class exchangerates {

    private $_statuses = array('ACTIVE', 'DISABLED', 'REMOVED');
    private $_currencies = array('AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
        'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP',
        'BYR', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF',
        'DKK', 'DOP', 'DZD', 'ECS', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GGP',
        'GHS', 'GIP', 'GMD', 'GNF', 'GWP', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS',
        'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW',
        'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LTL', 'LVL', 'LYD', 'MAD', 'MDL',
        'MGF', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD',
        'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG',
        'QAR', 'QTQ', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP',
        'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SVC', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP',
        'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST',
        'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW', 'ZWD');
    // -----------------------------------------------
    // -----------------------------------------------
    // DELIVERY AGENCIES
    // -----------------------------------------------
    // -----------------------------------------------
    private function __adjustExchangeRate (&$data) {
        if (isset($data['ID']))
            $data['ID'] = intval($data['ID']);
        if (isset($data['Rate']))
            $data['Rate'] = floatval($data['Rate']);
        return $data;
    }

    public function getExchangeRateByID ($agencyID) {
        global $app;
        $config = dbquery::shopGetExchangeRateByID($agencyID);
        $data = $app->getDB()->query($config);
        $data = $this->__adjustExchangeRate($data);
        return $data;
    }

    public function getExchangeRates_List (array $options = array()) {
        global $app;
        $config = dbquery::shopGetExchangeRatesList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getExchangeRateByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        $dataList['currencyList'] = $this->getCurrencyList();
        return $dataList;
    }

    public function createExchangeRate ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $rateID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CurrencyA' => array('string', 'notEmpty'),
            'CurrencyB' => array('string', 'notEmpty'),
            'Rate' => array('numeric', 'notEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (isset($validatedValues['Rate'])) {
                    $validatedValues['Rate'] = floatval($validatedValues['Rate']);
                }

                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                $configCreateOrigin = dbquery::shopCreateExchangeRate($validatedValues);

                $app->getDB()->beginTransaction();
                $rateID = $app->getDB()->query($configCreateOrigin) ?: null;

                if (empty($rateID))
                    throw new Exception('CurrencyCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($rateID))
            $result = $this->getExchangeRateByID($rateID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateExchangeRate ($id, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'CurrencyA' => array('string', 'skipIfUnset'),
            'CurrencyB' => array('string', 'skipIfUnset'),
            'Rate' => array('numeric', 'skipIfUnset'),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (isset($validatedValues['Rate'])) {
                    $validatedValues['Rate'] = floatval($validatedValues['Rate']);
                }

                $app->getDB()->beginTransaction();

                $configCreateCategory = dbquery::shopUpdateExchangeRate($id, $validatedValues);
                $app->getDB()->query($configCreateCategory);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getExchangeRateByID($id);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function createOrUpdateExchangeRate ($reqData) {
        $rate = null;
        if (isset($reqData['CurrencyA']) && isset($reqData['CurrencyB'])) {
            $rate = $this->getExchangeRateByBothRateNames($reqData['CurrencyA'], $reqData['CurrencyB']);
        }
        if (isset($rate['ID'])) {
            $reqData['Status'] = 'ACTIVE';
            return $this->updateExchangeRate($rate['ID'], $reqData);
        } else {
            return $this->createExchangeRate($reqData);
        }
    }

    public function deleteExchangeRate ($id) {
        global $app;
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();

            $config = dbquery::shopDeleteExchangeRate($id);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'CurrencyDeleteError';
        }

        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getDefaultDBPriceCurrency () {
        $currencyName = null;
        $prop = API::getAPI('shop:settings')->getSettingsMisc();
        if ($prop === null || empty($prop) || empty($prop['DBPriceCurrencyType'])) {
            $prop = API::getAPI('shop:settings')->createDefaultSettingsMisc();
            if (empty($prop) ||!$prop['success']) {
                throw new Exception('#[ShopExRt0003] NeedToDefineDBPriceCurrencyType');
            }
        }
        $currencyName = $prop['DBPriceCurrencyType'];
        $rate = $this->getExchangeRateFrom_ByCurrencyName($currencyName);
        if ($rate === null) {
            throw new Exception("#[ShopExRt0004] DefaultCurrencyNameIsMissingInDB_" . $currencyName, 1);
        }
        $this->__adjustExchangeRate($rate);
        return $rate;
        // if ($returnFullRate) {
        // }
        // return $currencyName;
    }

    public function getExchangeRateTo_ByCurrencyName ($currencyName) {
        global $app;
        $config = dbquery::shopGetExchangeRateTo_ByCurrencyName($currencyName);
        $rate = $app->getDB()->query($config) ?: array();
        return $rate;
    }

    public function getExchangeRateFrom_ByCurrencyName ($currencyName) {
        global $app;
        $config = dbquery::shopGetExchangeRateFrom_ByCurrencyName($currencyName);
        $rate = $app->getDB()->query($config) ?: array();
        return $rate;
    }

    public function convertToRates ($value, $baseCurrencyName = false, $exchangeRates = array()) {
        global $app;
        $conversions = array();

        if ($baseCurrencyName === false) {
            $defaultRate = $this->getDefaultDBPriceCurrency();
            if (!empty($defaultRate)) {
                $baseCurrencyName = $defaultRate['CurrencyA'];
            }
        }

        if (count($exchangeRates) === 0) {
            $exchangeRates = $this->getAvailableConversionOptions($baseCurrencyName);
        }

        // this is the value for base currency
        $conversions[$baseCurrencyName] = $value;
        // and here we go through others
        foreach ($exchangeRates as $currencyName => $exchangeRate) {
            // var_dump($currencyName);
            // var_dump($exchangeRate);
            // var_dump($value);
            if ($baseCurrencyName !== $currencyName)
                $conversions[$currencyName] = floatval(number_format($value * $exchangeRate, 0, '.', ''));
        }

        return $conversions;
    }

    public function getAvailableConversionOptions ($baseCurrencyName = false) {
        global $app;
        $valueCurrencyName = null;
        if (empty($baseCurrencyName)) {
            $rate = $this->getDefaultDBPriceCurrency();
            if (!empty($rate)) {
                $valueCurrencyName = $rate['CurrencyA'];
            }
        } else {
            $valueCurrencyName = $baseCurrencyName;
        }

        $config = dbquery::shopGetExchangeRatesList(array(
            'fields' => array('CurrencyA', 'CurrencyB', 'Rate'),
            'limit' => 0
        ));
        $config['condition']['CurrencyA'] = $app->getDB()->createCondition($valueCurrencyName);
        $availableRates = $app->getDB()->query($config) ?: array();
        $data = array();

        $data[$valueCurrencyName] = 1.0;

        foreach ($availableRates as $value) {
            $value = $this->__adjustExchangeRate($value);
            $data[$value['CurrencyB']] = $value['Rate'];
        }

        return $data;
    }

    public function getActiveExchangeRatesList () {
        $rates = $this->getExchangeRates_List(array(
            "limit" => 0
        ));
        return $rates;
    }
    public function getCurrencyList () {
        return $this->_currencies;
    }
    public function getAllUserUniqCurrencyNames () {
        global $app;
        $config = dbquery::shopGetExchangeRatesList(array(
            'fields' => array('CurrencyA', 'CurrencyB'),
            'limit' => 0
        ));
        $userRates = $app->getDB()->query($config) ?: array();
        $data = array();

        foreach ($userRates as $value) {
            $data[$value['CurrencyA']] = true;
            $data[$value['CurrencyB']] = true;
        }

        return array_keys($data);
    }
    public function getExchangeRateByBothRateNames ($baseCCY, $CCY) {
        global $app;
        $config = dbquery::shopGetExchangeRateByBothNames($baseCCY, $CCY);
        $rate = $app->getDB()->query($config) ?: array();
        return $rate;
    }
    public function getActiveRateMultipliers () {
        $baseRate = $this->getDefaultDBPriceCurrency();
        $rates = $this->getAvailableConversionOptions();
        foreach ($rates as $key => $value) {
            $rates[$key] = 1 / $value;
        }
        return $rates;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        if (isset($req->get['type'])) {
            switch ($req->get['type']) {
                case 'currencylist':
                    $resp = $this->getCurrencyList();
                    break;
                case 'userlist':
                    $resp = $this->getAllUserUniqCurrencyNames();
                    break;
                case 'privatbank': {
                    $ch = curl_init();
                    $url = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=11';
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $resp = json_decode(curl_exec($ch), true);
                    curl_close($ch);
                    break;
                }
            }
        } elseif (empty($req->get['id'])) {
            $resp = $this->getExchangeRates_List($req->get);
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->getExchangeRateByID($agencyID);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createOrUpdateExchangeRate($req->data);
        // $this->_getOrSetCachedState('changed:agencies', true);
    }

    public function patch (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->updateExchangeRate($agencyID, $req->data);
            // $this->_getOrSetCachedState('changed:agencies', true);
        }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->deleteExchangeRate($agencyID);
            // $this->_getOrSetCachedState('changed:agencies', true);
        }
    }
}

?>