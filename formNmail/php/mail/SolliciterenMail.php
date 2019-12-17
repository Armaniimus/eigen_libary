<?php
$error = "";
$mailingType = "";
if (isset($_POST["submit"])) {
    // echo "submit";
    $error = "";

    // check if personalia are there
    if ( !isset($_POST["naam"]) ) {$error = "no namefield";}
    elseif ( !isset($_POST["email"]) ) {$error = "no email field";}
    elseif ( !isset($_POST["tel"]) ) {$error = "no tel field";}
    elseif ( !isset($_POST["adres"]) ) {$error = "no adress field";}

    // check if vooropleiding is there
    elseif ( !isset($_POST["niveau"]) ) {$error = "no niveau field";}

    // check if werkervaring is there
    elseif ( !isset($_POST["functie"]) ) {$error = "no functie field";}
    elseif ( !isset($_FILES["cv"]) ) {$error = "no cv field";}


    // Check if fields are filled
    else {

        // Check personalia are filled
        if      ( trim( $_POST['naam'] ) == "" )             {$error = 'name field not filled';}

        elseif  ( trim( $_POST['email'] ) == "" )            {$error = 'email field not filled';}
        elseif  ( strlen( trim( $_POST['email'] ) ) < 7 )    {$error = 'email is shorter then 7 characters';}

        elseif  ( trim( $_POST['tel'] ) == "" )              {$error = 'tel field not filled';}
        elseif  ( strlen( trim( $_POST['tel'] ) ) < 7 )      {$error = 'tel is shorter then 7 characters';}

        elseif  ( trim( $_POST['adres'] ) == "" )            {$error = 'adres field not filled';}
        elseif  ( strlen( trim( $_POST['adres'] ) ) < 8 )    {$error = 'adres is shorter then 8 characters';}

        // Check if information is filled
        elseif  ( trim( $_POST['niveau'] ) == "" )           {$error = 'niveau field not filled';}
        elseif  ( strlen( trim( $_POST['niveau'] ) ) < 1 )   {$error = 'niveau is shorter then 1 characters';}

        elseif  ( trim( $_POST['functie'] ) == "" )          {$error = 'functie field not filled';}
        elseif  ( strlen( trim( $_POST['functie'] ) ) < 1 )  {$error = 'functie is shorter then 1 characters';}
        else {
            sendMailVacature();
        }
    }
}

// echoTestData($error, $mailingType);
function echoTestData($error, $mailingType) {
    echo "error:" . $error;
    echo "<br>";
    echo "mailingtype:" . $mailingType;
    echo "<br>";
    echo "<br>";
}

function sendMailVacature() {
    require_once "MailLib.php";
    $mailLib = new MailLib();

    //send mail to siteowner
    $to = "avanalphen@noombla.nl";
    // $to = "jspilker@noombla.nl";
    $subject = 'Sollicitatie op Noombla.nl: ';
    $message =
        "Verzoek verkregen van noombla.nl/vacature-formulier \n"
        . "en komt van: \n"
        . "    Naam: " . $_POST['naam'] . "\n"
        . "    E-mail: " . $_POST['email'] . "\n"
        . "    TelefoonNr: " . $_POST['tel'] . "\n"
        . "\n"
        . "    Opleidingsniveau: " . $_POST['niveau'] . "\n"
        . "    Gewenste functie: " . $_POST['functie'] . "\n"
    ;

    // check if there are uploads
    if ( isset($_FILES["cv"]) && ($_FILES["cv"]["error"] == 0) ) {
        $tmp_Name = $_FILES['cv']['tmp_name'];
        $uploadDir = __DIR__ . "/mailTmpFileUploads";
        $name = basename( $_FILES['cv']["name"] );
        $fileNameCv = "$uploadDir/0$name";
        move_uploaded_file ($tmp_Name, $fileNameCv);
    } else {
        $fileNameCv = NULL;
    }

    // decide how to handle the mailing
    if ( $fileNameCv ) {
        $mailLib->attachmentMail($to, $subject, $message, $fileNameCv);
        $mailingType = 'Mail with attachment';
        unlink($fileNameCv); //delete file after mailing

    } else {
        $mailLib->regularMail($to, $subject, $message);
        $mailingType = 'Regular mail';
    }

    if ( $_POST['email'] !== "" ) {
        //send mail to requesting client
        $mailLib = new MailLib();
        $to = $_POST['email'];
        $subject = "uw sollicitatie aan Noombla BV";
        $message = 'uw sollicitatie is successvol verstuurd uw krijgt binnekort bericht terug';
        $mailLib->regularMail($to, $subject, $message);
    }
}
