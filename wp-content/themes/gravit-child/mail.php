<?php

   if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL); 
    $to = 'info@31stbrklyn.com';
    $fname =$_POST["fName"]; 
    $lname = $_POST["lName"];
    $mesg = $_POST["mesg"];
    $headers = "Reply-To:" . $email . "\r\n";
    
    
    $subject = "You have a message sent from your site";
    $fields = array(); 
    $fields{"FirstName"} = "First Name";
    $fields{"LastName"} = "Last Name";
    $fields{"Email"} = "Email"; 
    $fields{"Message"} = "Message";
    $body = "Here is what was sent:\r\n"; foreach($fields as $a => $b){   $body .= sprintf("%20s: %s\n",$b,$_REQUEST[$a]); }
    
    if (mail($to, $subject, $body, $headers)){
    
     http_response_code(200);
     echo 'success';

    }
    else{
        echo 'error';
    }
 }

?>