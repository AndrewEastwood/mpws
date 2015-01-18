<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;
use curl_init;

class exchangerates extends \engine\objects\api {

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
        $config = shared::jsapiShopGetExchangeRateByID($agencyID);
        $data = $this->getCustomer()->fetch($config);
        $data = $this->__adjustExchangeRate($data);
        return $data;
    }

    public function getExchangeRates_List (array $options = array()) {
        $config = shared::jsapiShopGetExchangeRatesList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getExchangeRateByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        $dataList['currencyList'] = $this->getCurrencyList();
        return $dataList;
    }

    public function createExchangeRate ($reqData) {
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

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateOrigin = shared::jsapiShopCreateExchangeRate($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $rateID = $this->getCustomer()->fetch($configCreateOrigin) ?: null;

                if (empty($rateID))
                    throw new Exception('CurrencyCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = shared::jsapiShopUpdateExchangeRate($id, $validatedValues);
                $this->getCustomer()->fetch($configCreateCategory);

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = shared::jsapiShopDeleteExchangeRate($id);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
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
        $prop = $this->getAPI()->settings->findByName('DBPriceCurrencyType');
        if ($prop === null) {
            throw new Exception('#[ShopExRt0003] NeedToDefineDBPriceCurrencyType');
        }
        $currencyName = $prop['Value'];
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
        $config = shared::jsapiShopGetExchangeRateTo_ByCurrencyName($currencyName);
        $rate = $this->getCustomer()->fetch($config) ?: array();
        return $rate;
    }

    public function getExchangeRateFrom_ByCurrencyName ($currencyName) {
        $config = shared::jsapiShopGetExchangeRateFrom_ByCurrencyName($currencyName);
        $rate = $this->getCustomer()->fetch($config) ?: array();
        return $rate;
    }

    public function convertToRates ($value, $baseCurrencyName = false, $exchangeRates = array()) {
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
        $valueCurrencyName = null;
        if (empty($baseCurrencyName)) {
            $rate = $this->getDefaultDBPriceCurrency();
            if (!empty($rate)) {
                $valueCurrencyName = $rate['CurrencyA'];
            }
        } else {
            $valueCurrencyName = $baseCurrencyName;
        }

        $config = shared::jsapiShopGetExchangeRatesList(array(
            'fields' => array('CurrencyA', 'CurrencyB', 'Rate'),
            'limit' => 0
        ));
        $config['condition']['CurrencyA'] = shared::createCondition($valueCurrencyName);
        $availableRates = $this->getCustomer()->fetch($config) ?: array();
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
        $config = shared::jsapiShopGetExchangeRatesList(array(
            'fields' => array('CurrencyA', 'CurrencyB'),
            'limit' => 0
        ));
        $userRates = $this->getCustomer()->fetch($config) ?: array();
        $data = array();

        foreach ($userRates as $value) {
            $data[$value['CurrencyA']] = true;
            $data[$value['CurrencyB']] = true;
        }

        return array_keys($data);
    }
    public function getExchangeRateByBothRateNames ($baseCCY, $CCY) {
        $config = shared::jsapiShopGetExchangeRateByBothNames($baseCCY, $CCY);
        $rate = $this->getCustomer()->fetch($config) ?: array();
        return $rate;
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
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createOrUpdateExchangeRate($req->data);
        // $this->_getOrSetCachedState('changed:agencies', true);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
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
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
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