<?php

//set parameters
        $api_key = "key";
        $xpub = "xpub";
        $secret = "random";
        $rootURL = "url";
        $orderID = uniqid();

//call blockchain info receive payments API
        $callback_url = $rootURL."/callback.php?invoice=".$orderID."&secret=".$secret;
        $receive_url = "https://api.blockchain.info/v2/receive?key=".$api_key."&xpub=".$xpub."&callback=".urlencode($callback_url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $receive_url);
        $ccc = curl_exec($ch);
        $json = json_decode($ccc, true);
        $pay = $json['address']; //the newly created address will be stored under 'address' in the JSON response
		    //echo $payTo; //echo out the newly created receiving address


?>

<a href="bitcoin:<?=$pay?>"><img src="https://blockchain.info/qr?data=<?=$pay?>&size=200"></a>
