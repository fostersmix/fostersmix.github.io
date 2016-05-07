<?php

//set parameters
        $api_key = "4f46c7fc-2df4-4c2f-941b-8db479ab7c39";
        $xpub = "xpub6Bxgb2629tWYnYe8Le12CrZrd1FTBYUP87eqr5AYgytD1LueD2mNHD1DaGASGwGJ5nQgEsTyGmmX7RTZPySTVMDzn1G7bPqet6FFNph9BPh";
        $secret = "abc123"; //this can be anything you want
        $rootURL = "http://fostersmix.github.io/blockchain-qr/"; //example https://mysite.org  or http://yourhomepage.com/store
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
