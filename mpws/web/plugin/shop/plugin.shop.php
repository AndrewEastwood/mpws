<?php

class pluginShop extends objectBaseWebPlugin {  

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        //echo '111OLOLO';
        // echo "<br><br>getInnerMethod = " . $ctx->getLastCommand()->getInnerMethod();
        switch($ctx->getLastCommand(false)->getInnerMethod()) {
            case 'default' : 
            default :
                $rez = $this->_displayPage_Default();
                break;
        }

        // $ctx->pageModel->addMessage('1234');
        return $rez;

    }

    private function _displayPage_Default () {
        $display = libraryRequest::getDisplay();
        switch ($display) {
            case "products" :
            case "categories" :
            case "origins" :
            case "specifications" :
            case "currency" :
            case "commands" :
                $managerName = 'Manager' . ucfirst($display);
                $this->getWidget('ActionHandlerStandartDataTableManager', $managerName);
                break;
            case "api" :
                if ($this->isActive())
                    $this->getWidget('AddDataApiViewer', 'ApiShop');
        }
 
        /*echo '_displayQueue';

        $this->store_storeSet('TEMPLATE.PATH', $this->res_getResource('page.queue.datatable'));
        $this->store_storeSet('TEMPLATE.NAME', 'page.queue.datatable');
        //$pModel = &$this->getModel();
        
        //var_dump($store);
        //$store['TEMPLATE'] = $this->getTemplate('page.queue.datatable');
        
        
        // menu component
        $menu = libraryView::getLinks($this->getConfiguration('GENERAL', 'MENU'));
        
        //var_dump($menu);
        
        $this->addComponent('MENU', $menu, 'menu_list');*/
        
    }

        
    /* PLUGIN SPEC METHODS */
    
    private function _displayProducts () {
        
    }



    /* PLUGIN API METHODS */

    private function _api_getProducts () {

    }
    
    private function _api_getCurrency () {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_widget_customMonitor;
        $reports = $ctx->contextCustomer->getDBO()
                ->reset()
                ->select($cfg['fields'])
                ->from($cfg['source'])
                ->fetchData();

    }
    
    protected function _jsapiTriggerAsPlugin() {
        // echo "QQQTEST";
        parent::_jsapiTriggerAsPlugin();
        $param = libraryRequest::getApiParam();
        $rez = false;

        // extract params
        // some functions require particular parameters to be not empty
        // otherwise you will get error message
        $pProductID = !empty($param['pid']) ? $param['pid'] : false;
        $pCategoryID = !empty($param['cid']) ? $param['cid'] : false;
        $pOriginID = !empty($param['oid']) ? $param['oid'] : false;
        $pLimit = !empty($param['limit']) ? $param['limmit'] : false;
        $pOffset = !empty($param['offset']) ? $param['offset'] : false;

        // token=656c88543646e400eb581f6921b83238
        // var_dump($param);
        $ctx = contextMPWS::instance();
        switch(libraryRequest::getApiFn()) {
            case "product_list" : {
                // echo "LOL";
                // echo 'with fmt = ',  fmtJSON;
                $cfg = $this->objectConfiguration_data_jsapiProductList;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);
                $ctx->pageModel->addStaticData($p);
                break;
            }
            case "category_list" : {
                // echo "LOL";
                // echo 'with fmt = ',  fmtJSON;
                $cfg = $this->objectConfiguration_data_jsapiCategoryList;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);
                $ctx->pageModel->addStaticData($p);
                break;
            }
            case "origin_list" : {
                // echo "LOL";
                // echo 'with fmt = ',  fmtJSON;
                $cfg = $this->objectConfiguration_data_jsapiOriginList;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);
                $ctx->pageModel->addStaticData($p);
                break;
            }
            case "products" : {
                $cfg = $this->objectConfiguration_data_jsapiProducts;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);
                $ctx->pageModel->addStaticData($p);
                break;
            }
            case "products_sale_short" : {
                $cfg = $this->objectConfiguration_data_jsapiProductsSaleShort;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);
                $ctx->pageModel->addStaticData($p);
                break;
            }
            case "products_price_stats" : {
                $cfg = $this->objectConfiguration_data_jsapiProductsPriceStats;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);

                $_dt = $p->getData();
                $_tmp = array();
                foreach ($_dt as $key => $value) {
                    $value['Prices'] = explode(EXPLODE, $value['Prices']);
                    $_tmp[$key] = $value;
                }

                // $ctx->pageModel->addStaticData($p->toJSON());
                $ctx->pageModel->addStaticData(libraryUtils::getJSON($_tmp));
                break;
            }
            case "category_single_short" : {
                break;
            }
            case "category_single_full" : {
                break;
            }
            case "origin_single_short" : {
                break;
            }
            case "origin_single_full" : {
                break;
            }
            case "product_single_short" : {
                break;
            }
            case "product_single_full" : {
                // $p = false;
                // if (empty($pProductID))
                //     $data['error'] = libraryUtils::getJSON('Empty product ID parameter');
                // else {


                    $cfg = $this->objectConfiguration_data_jsapiProductSingleFull;
                    $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);


                // }


                $ctx->pageModel->addStaticData($p->toJSON());
                break;
            }
            case "shop_map" : {

            }
            case "products_most_popular" : {

            }
            case "products_sale_full" : {
                $cfg = $this->objectConfiguration_data_jsapiProductsSaleFull;
                $p = $ctx->contextCustomer->getDBO()->mpwsFetchData($cfg['data']);

                $_tmp = array();
                $_dt = $p->getData();
                // merge

                $mergeKey = 'ID';
                $mergePropNameAsKey = 'ProductAttribute';
                $mergePropNameAsValue = 'ProductValue';
                $mergeDestKey = 'ProductAttributes';

                foreach ($_dt as $value) {
                    if (empty($_tmp[$value['ID']]))
                        $_tmp[$value['ID']] = $value;
                    else {
                        $keys = $_tmp[$value['ID']][$mergePropNameAsKey];
                        $values = $_tmp[$value['ID']][$mergePropNameAsValue];

                        if (!is_array($keys))
                            $keys = array($keys, $value[$mergePropNameAsKey]);

                        if (!is_array($values))
                            $values = array($values, $value[$mergePropNameAsValue]);

                        $_tmp[$value['ID']][$mergeDestKey] = array_combine($keys, $values);

                        unset($_tmp[$value['ID']][$mergePropNameAsKey]);
                        unset($_tmp[$value['ID']][$mergePropNameAsValue]);
                        // $_tmp[$value['ID']] = array_merge_recursive($_tmp[$value['ID']], $value);
                    }
                }

                // $_clean = array();
                // foreach ($_tmp as $key => $value) {
                //     $value[$mergeDestKey] = array_combine($value[$mergePropNameAsKey], $value[$mergePropNameAsValue]);
                //     $_clean[] = $value;
                // }





                $ctx->pageModel->addStaticData(libraryUtils::getJSON($_tmp));
                break;
            }
        }
        // put data
        // $ctx->pageModel->addStaticData("HELLO WORLD!");
    }
}
    
?>