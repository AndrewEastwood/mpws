<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  </head>
  <body>
    <div class="MPWSAntibotRootContainer" id="MPWSAntibotRootContainerID">
<?php

require_once('recaptchalib.php');

// Get a key from https://www.google.com/recaptcha/admin/create

                    $publickey = "6LdZW8kSAAAAAP8AWKV4F7doiL94Us3rD7ivoa0Z";
                    //$privatekey = "6LdZW8kSAAAAAPykUvwzdVGa6b-xGFAppTINSxJV";

# the response from reCAPTCHA
//$resp = null;
# the error code from reCAPTCHA, if any
$error = null;
/*
# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                echo "You got it!";
        } else {
                # set the error code so that we can display it
                $error = $resp->error;
        }
}
*/
if (isset($_GET['error']))
    $error = $_GET['error'];

echo recaptcha_get_html($publickey, $error);


?>
    </div>
    
    <script>
    
        //document.domain = 'pobutteh.com.ua';
        
        $(document).ready(function(){
            //alert(window.parent);
            //alert(window.parent.$('#MPWSWidgetReCaptchaContainerID'));
            
            //setTimeout("injectWidget()", 3000);
        });
        
        function injectWidget () {
            window.parent.$('#MPWSWidgetReCaptchaContainerID').html($('#MPWSAntibotRootContainerID').html());
            window.parent.$('#MPWSAntiBotWidgetEmbeddedframeID').hide();
        }
        
    </script>
  </body>
</html>