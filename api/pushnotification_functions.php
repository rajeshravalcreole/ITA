<?php

function sendPushNotification($notification, $deviceToken)
{
    $ch = curl_init(FCM_URL);
 
    //The device token.
    //$deviceToken = "c49uxssWcG8:APA91bGntZINXsJuxDnW-lZ7rghTZaEcz0ZujrA-j7vspXC4xEP9cKQ5ntp9HVmuZCHS8mtuooQo2vQCER5ocTeeRvl32Dq_yABQ_N9osbn2j4blrfcwHOCZr_jAtQp7W_APl656m91z"; //token here

    //This array contains, the token and the notification. The 'to' attribute stores the token.
    $arrayToSend = array('to' => $deviceToken, 'notification' => $notification,'priority'=>'high');

    //Generating JSON encoded string form the above array.
    $json = json_encode($arrayToSend);
    //Setup headers:
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='.FCM_KEY; // key here

    //Setup curl, add headers and post parameters.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

    //Send the request
    $response = curl_exec($ch);
    //Close request
    curl_close($ch);
    //return $response;
}


?>