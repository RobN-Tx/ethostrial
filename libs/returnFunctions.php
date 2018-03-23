<?php

function processIncomingUserData($readablePayload){

    foreach ($readablePayload as $token => $token_value) {
        

        
        if ($token == "emails") {
            $_SESSION["emails"]=$token_value[0];
            
        }else{
            $_SESSION[$token]=$token_value;
        };
    };


}

function checkIfUserIsRegistered(){
    error_log("checkIfUserIsRegistered");
    $sessionVariableName = "oid";
    if (!isset($_SESSION[$sessionVariableName])) {
        $accessToken = 500; //error number for access token
    } else {
        $incomingName = $_SESSION[$sessionVariableName];
        echo $incomingName;
        //go and ask mysql directory table if the oid is known and if known if they are registered (==yes)
        error_log($sessionVariableName.$incomingName.'registered_user');
        $checkRegistered = DirectoryfetchSQLColumnData($sessionVariableName, $incomingName, 'registered_user');
        echo "<br>".$checkRegistered;
        error_log($checkRegistered.'check registerred response <---');
        if($checkRegistered == 'yes'){
            $accessToken = 0;
        } else {
            $accessToken = 9000;
        }

    }
    error_log("access token!!!!");
    error_log($accessToken,0);
    return $accessToken;
}

?>