<?php

class pluginAccount extends objectPlugin {

    public function getResponse () {

        switch(libraryRequest::getValue('fn')) {
            case "create": {
                $data = $this->_custom_api_createAccount();
                break;
            }
            case "signin": {
                $data = $this->_custom_api_signin();
                break;
            }
        }

        // attach to output
        return $data;
    }

    private function _custom_api_signin () {
        $accountObj = new libraryDataObject();

        $credentials = libraryRequest::getPostValue('credentials');

        if (empty($dataAccount['login']))
            $errors['login'] = 'Empty';

        if (empty($dataAccount['password']))
            $errors['password'] = 'Empty';

        $dataAccount = $this->getAccount();

        return $accountObj;
    }

    private function _custom_api_createAccount () {
        $dataAccount = libraryRequest::getPostValue('account');

        $accountObj = new libraryDataObject();
        $errors = array();

        if (empty($dataAccount['FirstName']))
            $errors['FirstName'] = 'Empty';

        if (empty($dataAccount['LastName']))
            $errors['LastName'] = 'Empty';

        if (empty($dataAccount['EMail']))
            $errors['EMail'] = 'Empty';

        if (empty($dataAccount['Password']))
            $errors['Password'] = 'Empty';

        if ($dataAccount['Password'] != $dataAccount['ConfirmPassword'])
            $errors['ConfirmPassword'] = 'WrongConfirmPassword';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("values", $dataAccount);
            return $accountObj;
        }

        unset($dataAccount['ConfirmPassword']);

        $dataAccount['Password'] = md5($dataAccount['Password']);
        $dataAccount['DateCreated'] = date('Y:m:d H:i:s');
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');

        $this->getCustomer()-> addAccount($dataAccount);

        $accountObj->setData("success", true);

        return $accountObj;
    }

}

?>