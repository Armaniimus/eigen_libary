<?php
    //check if the form was submitted
    if ( isset($_POST['submit']) ) {

        //start field exist checks
        if      ( !isset($_POST['name']) )      {$error = 'name field missing';}
        elseif  ( !isset($_POST['email']) )     {$error = 'email field missing';}
        elseif  ( !isset($_POST['subject']) )   {$error = 'subject field missing';}
        elseif  ( !isset($_POST['message']) )   {$error = 'message field missing';}
        else {

            //start if fields are filled checks
            if      ( trim( $_POST['name'] ) == "" )             {$error = 'name field not filled';}

            elseif  ( trim( $_POST['email'] ) == "" )            {$error = 'email field not filled';}
            elseif  ( strlen( trim( $_POST['email'] ) ) < 7 )    {$error = 'email is shorter then 7 characters';}

            elseif  ( trim( $_POST['subject'] ) == "" )          {$error = 'subject field not filled';}
            elseif  ( strlen( trim( $_POST['subject'] ) ) < 3 )  {$error = 'subject is shorter then 3 characters';}

            elseif  ( trim( $_POST['message'] ) == "" )          {$error = 'message field not filled';}
            elseif  ( strlen( trim( $_POST['message'] ) ) < 15 ) {$error = 'message field is shorter then 15 characters';}
            else {
                sendMailContact();
                $error = 'success';
            }
        }
    } else {
        $error = 'no submit';
    }
    // echoTestData($error);

    function echoTestData($error) {
        echo $error;
    }


    function sendMailContact() {
        require_once "MailLib.php";
        $mailLib = new MailLib();

        //send mail to siteowner
        $to = "jspilker@noombla.nl";
        // $to = "avanalphen@noombla.nl";
        $subject = 'ContactVerzoek Noombla.nl: ' . $_POST['subject'];
        $message =
            "Verzoek verkregen van noombla.nl/contact \n"
            . "en komt van: \n"
            . "    Name: " . $_POST['name'] . "\n"
            . "    E-mail: " . $_POST['email'] . "\n"
            . "\n"
            . $_POST['message']
        ;
        $mailLib->regularMail($to, $subject, $message);

        //send mail to requesting client
        $mailLib = new MailLib();
        $to = $_POST['email'];
        $subject = "uw vraag aan Noombla BV";
        $message = 'uw verzoek is successvol verstuurd u krijgt binnekort bericht terug';
        $mailLib->regularMail($to, $subject, $message);
    }
?>
