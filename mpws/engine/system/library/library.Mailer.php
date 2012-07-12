<?php

class libraryMailer {

    public static function getEMailDomain () {
        $_domain = $_SEREVER['HTTP_HOST'];
        $_parts = explode('.', strtolower($_domain));
        if ($_parts[0] === 'www')
            unset($_parts[0]);
        return implode('.', $_parts);
    }

    public static function sendEMail ($recipients) {

        $_recipients = array();

        // check for multi-addressees
        if (isset($recipients['TO']))
            $_recipients[] = $recipients;
        if (isset($recipients[0]['TO']))
            $_recipients = $recipients;

        if (empty($_recipients))
            return false;

        foreach ($_recipients as $_recipient) {
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: ' . $_recipient['CONTENT_TYPE'] . "\r\n";
            // Additional headers
            $headers .= 'To: ' . $_recipient['NAME'] . ' <' . $_recipient['TO'] . '>' . "\r\n";
            $headers .= 'From:  ' . $_recipient['FROM'] . "\r\n";
            // send mail
            mail($_recipient['TO'], $_recipient['SUBJECT'], $_recipient['MESSAGE'], $headers);
        }
    }

}

?>
