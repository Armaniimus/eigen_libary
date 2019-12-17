<?php
$error = "";

if ( isset($_POST['submit']) ) {
    //start field exist checks
    if      ( !isset($_POST['tel']) )      {$error = 'tel field missing';}
    else {
        if          ( $_POST['tel'] == "" )                         {$error = 'tel field is empty';}
        elseif      ( strlen( trim( $_POST['tel'] ) ) < 5 )         {$error = 'tel field is is shorter then 5 characters';}
        elseif      ( strlen( trim( $_POST['tel'] ) ) > 20 )        {$error = 'tel field is is longer then 20 characters';}
        elseif      ( preg_match('/[^0-9 \- +]/', $_POST['tel'] ) ) {$error = 'tel field contains other characters then 0-9, - and +';}
        else {
            sendMailAdditioneleDiensten();
        }
    }
} else {
    $error = "success";
}

// echoTestData($error);
function echoTestData($error) {
    echo $error;
}

function sendMailAdditioneleDiensten() {
    require_once "MailLib.php";
    $mailLib = new MailLib();

    // send mail to siteowner
    $to = "jspilker@noombla.nl";
    // $to = "avanalphen@noombla.nl";
    $subject = 'Vraag over dienst verlening:';
    $message =
        "Verzoek verkregen van noombla.nl/additioneleDiensten \n"
        . "verzoek om volgende nummer terug te bellen: " . $_POST['tel'] . "\n";

    $mailLib->regularMail($to, $subject, $message);
}


?>
