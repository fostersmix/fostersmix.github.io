<?php
   $secret = "from-generate";
   if($_GET['secret'] != $secret)
   {
     die('Stop doing that');
   }
   else {
     $fff = fopen("test.txt", "w");
     $value = $_GET['value']." - ";
     $fw = fwrite($fff, $value);
     $txhash = $_GET['transaction_hash']." - ";
     $fw = fwrite($fff, $txhash);
     $invoice = $_GET['invoice'];
     $fw = fwrite($fff, $invoice);
     fclose($fff);
     echo "*ok*";
   }
?>
