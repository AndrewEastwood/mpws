<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

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
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRateByID($agencyID);
        $data = $this->getCustomer()->fetch($config);
        $data = $this->__adjustExchangeRate($data);
        return $data;
    }

    public function getExchangeRates_List (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRatesList($options);
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

                $configCreateOrigin = $this->getPluginConfiguration()->data->jsapiShopCreateExchangeRate($validatedValues);

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
            'Rate' => array('numeric', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (isset($validatedValues['Rate'])) {
                    $validatedValues['Rate'] = floatval($validatedValues['RateA']);
                }

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopUpdateExchangeRate($id, $validatedValues);
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

    public function deleteExchangeRate ($id) {
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = $this->getPluginConfiguration()->data->jsapiShopDeleteExchangeRate($id);
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

    public function getDefaultDBPriceCurrencyType ($returnFullRate = false) {
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
        if ($returnFullRate) {
            return $rate;
        }
        return $currencyName;
    }

    public function getExchangeRateTo_ByCurrencyName ($currencyName) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRateTo_ByCurrencyName($currencyName);
        $rate = $this->getCustomer()->fetch($config) ?: array();
        return $rate;
    }

    public function getExchangeRateFrom_ByCurrencyName ($currencyName) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRateFrom_ByCurrencyName($currencyName);
        $rate = $this->getCustomer()->fetch($config) ?: array();
        return $rate;
    }

    public function convertBaseValueToRate ($value, $toCurrency, $rate = 0) {
        $convertedValue = -1;
        $baseCurrency = $this->getExchangeRateFrom_ByCurrencyName();
        $rateFrom = $this->getExchangeRateToByCurrencyName($baseCurrency);
        if ($rateFrom !== null) {
            if ($rate > 0) {
                $convertedValue = floatval(number_format($value * $rate, 0, '.', ''));
            } else {
                // use current rate value
                $rateTo = $this->getExchangeRateTo_ByCurrencyName($toCurrency);
                if ($rateTo === null) {
                    throw new Exception("#[ShopExRt0001] CanNotFindRateTo", 1);
                }
                $convertedValue = floatval(number_format($value * $rateTo['rate'], 0, '.', ''));
            }
        } else {
            throw new Exception("#[ShopExRt0004] CanNotFindBaseCurrency_" . $toCurrency);
        }
        if ($convertedValue < 0) {
            throw new Exception("#[ShopExRt0002] UnableToConvertValueTo_" . $toCurrency . "_from_" . $baseCurrency . "_value_" . $value);
        }
        return $convertedValue;
    }

    public function convertToDefinedRates ($value, $valueCurrency = false, $rates = array()) {
        $conversions = array();

        if ($valueCurrency === false) {
            $valueCurrency = $this->getDefaultDBPriceCurrencyType();
        }

        if (count($rates) === 0) {
            $rates = $this->getAvailableConversionOptions();
        }

        // this is the value for base currency
        $conversions[$valueCurrency] = $value;
        // and here we go through others
        foreach ($rates as $exchnageRate) {
            $conversions[$exchnageRate['currency']] = floatval(number_format($value * $exchnageRate['rate'], 0, '.', ''));
        }

        return $conversions;
    }

    public function getAvailableConversionOptions () {
        $valueCurrency = $this->getDefaultDBPriceCurrencyType();

        $condition = array();
        $condition['CurrencyA'] = $this->getPluginConfiguration()->data->jsapiCreateDataSourceCondition($valueCurrency);

        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRatesList(array(
            'fields' => array('CurrencyA', 'CurrencyB', 'Rate'),
            'condition' => $condition,
            'limit' => 0
        ));
        $availableRates = $this->getCustomer()->fetch($config) ?: array();
        $data = array();

        foreach ($availableRates as $value) {
            $value = $this->__adjustExchangeRate($value);
            $data[$value['CurrencyA']] = array(
                'rate' => $value['Rate'],
                'currency' => $value['CurrencyB']
            );
        }
        
        // $rates = $this->getActiveExchangeRatesAll();

        // // this is the value for base currency
        // $options[] = $valueCurrency;

        // foreach ($rates as $exchnageRate) {
        //     if ($exchnageRate['name'] === $valueCurrency) {
        //         $options[] = $exchnageRate['currency'];
        //     }
        // }
        return $data;
    }

    public function getActiveExchangeRatesAll () {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRatesList(array(
            'fields' => array('CurrencyA', 'CurrencyB', 'Rate'),
            'limit' => 0
        ));
        $userRates = $this->getCustomer()->fetch($config) ?: array();
        $data = array();

        foreach ($userRates as $value) {
            $value = $this->__adjustExchangeRate($value);
            $data[$value['CurrencyA']] = array(
                'rate' => $value['Rate'],
                'currency' => $value['CurrencyB']
            );
        }
        return $data;
    }
    public function getActiveExchangeRatesList () {
        $deliveries = $this->getExchangeRates_List(array(
            "limit" => 0
        ));
        return $deliveries;
    }
    public function getCurrencyList () {
        return $this->_currencies;
    }
    public function getAllUserUniqCurrencyNames () {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRatesList(array(
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

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        if (isset($req->get['type']) && $req->get['type'] === 'currencylist') {
            $resp = $this->getCurrencyList();
        } elseif (isset($req->get['type']) && $req->get['type'] === 'userlist') {
            $resp = $this->getAllUserUniqCurrencyNames();
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
        $resp = $this->createExchangeRate($req->data);
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