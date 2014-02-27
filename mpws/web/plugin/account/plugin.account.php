<?php

class pluginAccount extends objectPlugin {

    public function getResponse () {

        switch(libraryRequest::getValue('fn')) {
            case "create": {
                $data = $this->_custom_api_createAccount();
                break;
            }
        }

        // attach to output
        return $data;
    }

    private function _custom_api_createAccount () {
        $accountData = libraryRequest::getPostValue('account');

        $accountObj = new libraryDataObject();
        $errors = array();


        if (empty($accountData['firstname']))
            $errors[] = 'firstname#Empty';

        if (empty($accountData['lastname']))
            $errors[] = 'lastname#Empty';

        if (empty($accountData['email']))
            $errors[] = 'email#Empty';

        if (empty($accountData['password']))
            $errors[] = 'password#Empty';

        if ($accountData['password'] != $accountData['confirm_password'])
            $errors[] = 'confirm_password#WrongConfirmPassword';

        if (count($errors))
            $accountObj->setError($errors);

        return $accountObj;
    }

}

?>