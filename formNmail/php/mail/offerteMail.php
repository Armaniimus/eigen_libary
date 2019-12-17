<?php

    // Set additional dienst boolean to true if all is correct
    $subAddDienstIsset0 = ( isset( $_POST['dienst'] ) );
    if ( $subAddDienstIsset0 ) {
        $subAddDienstIsset1 = ( trim( $_POST['dienst'] ) == "Additionele diensten" );
        $subAddDienstIsset2 = ( isset( $_POST['additioneleDienst'] ) );

        $addDienstIsset = (
            $subAddDienstIsset1 &&
            $subAddDienstIsset2
        );
    }

    //check if the form was submitted
    if ( isset($_POST['submit']) ) {

        //start field exist checks
        if      ( !isset($_POST['naam']) )              {$error = 'naam field missing';}
        elseif  ( !isset($_POST['email']) )             {$error = 'email field missing';}
        elseif  ( !isset($_POST['bedrijfsnaam']) )      {$error = 'bedrijfsnaam field missing';}
        elseif  ( !isset($_POST['tel']) )               {$error = 'tel field missing';}
        elseif  ( !isset($_POST['dienst']) )            {$error = 'dienst field missing';}
        else {

            //start if fields are filled checks
            if      ( trim( $_POST['naam'] ) == "" )                        {$error = 'naam field not filled';}

            elseif  ( trim( $_POST['email'] ) == "" )                       {$error = 'email field not filled';}
            elseif  ( strlen( trim( $_POST['email'] ) ) < 6 )               {$error = 'email is shorter then 6 characters';}
            elseif  ( strlen( trim( $_POST['email'] ) ) > 255 )             {$error = 'email is longer then 255 characters';}

            elseif  ( trim( $_POST['bedrijfsnaam'] ) == "" )                {$error = 'bedrijfsnaam field not filled';}
            elseif  ( strlen( trim( $_POST['bedrijfsnaam'] ) ) < 3 )        {$error = 'bedrijfsnaam is shorter then 3 characters';}
            elseif  ( strlen( trim( $_POST['bedrijfsnaam'] ) ) > 255 )      {$error = 'bedrijfsnaam is longer then 255 characters';}

            elseif  ( trim( $_POST['tel'] ) == "" )                         {$error = 'tel field not filled';}
            elseif  ( strlen( trim( $_POST['tel'] ) ) < 6 )                 {$error = 'tel field is shorter then 6 characters';}
            elseif  ( strlen( trim( $_POST['tel'] ) ) > 100 )               {$error = 'tel field is longer then 10 characters';}

            elseif  ( trim( $_POST['dienst'] ) == "" )                      {$error = 'dienst field not filled';}

            elseif  ( $addDienstIsset )  {
                if  ( trim( $_POST['additioneleDienst'] ) == "" )           {$error = 'additioneleDienst field not filled even though it is required';}
                else {
                    sendMailOfferte();
                    $error = 'success with additioneleDienst';
                }
            }

            else {
                sendMailOfferte();
                $error = 'success without additioneleDienst';
            }
        }
    } else {
        $error = 'no submit';
    }


    // echoTestData($error);
    function echoTestData($error) {
        echo $error;
    }


    function sendMailOfferte() {
        require_once "MailLib.php";
        $mailLib = new MailLib();

        //send mail to siteowner
        $to = "jspilker@noombla.nl";
        // $to = "avanalphen@noombla.nl";
        $subject = 'Offerte aanvraag: door' . $_POST['bedrijfsnaam'];
        $message =
            "Verzoek verkregen van noombla.nl/offerte \n"
            . "    en komt van: \n"
            . "    Name: " . $_POST['naam'] . "\n"
            . "    Bedrijf: " . $_POST['bedrijfsnaam'] . "\n"
            . "    E-mail: " . $_POST['email']. "\n"
            . "    Tel: " . $_POST['tel']. "\n"
            . "\n"
            . "betreft de dienst: " . $_POST['dienst'] . "\n"
        ;

        global $addDienstIsset;
        if ( $addDienstIsset ) {
            $message .= "    AdditioneDienst: " . $_POST['additioneleDienst'] ;
        }

        $mailLib->regularMail($to, $subject, $message);

        //send mail to requesting client
        $mailLib = new MailLib();
        $to = $_POST['email'];
        $subject = "uw offerte aanvraag noombla BV";
        $message = 'uw offerte aanvraag is verstuurd u krijgt zo snel mogenlijk bericht terug';
        $mailLib->regularMail($to, $subject, $message);
    }
?>
