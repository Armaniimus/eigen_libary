<?php
require_once "MailLib.php";
class testClass {
    public function __construct() {
        // $this->to = "avanalphen@noombla.nl";
        $this->to = "jspilker@noombla.nl";

        $this->subject = "new";
        $this->message = "your new message";

        $this->adressArray = [
            "tommy_chester@hotmail.com",
            "armand-van-alphen@outlook.com",
        ];

        $this->attach = 'C:\xampp\htdocs\websitenoombla2019\php\mail\testmail.php';
    }

    public function single() {
        $this->MailLib = new MailLib();
        $this->MailLib->regularMail($this->to, $this->subject, $this->message);
        echo "<br>test single [" . date("Y-m-d H:i:s") . "] <br>";
    }

    public function bulk() {
        $this->MailLib = new MailLib();
        $this->MailLib->regularBulk($this->adressArray, $this->subject, $this->message);
        echo "test bulk [" . date("Y-m-d H:i:s") . "] <br>";;
    }

    public function attach() {
        $this->MailLib = new MailLib();
        $this->MailLib->attachmentMail($this->to, $this->subject, $this->message, $this->attach);
        echo "test attach [" . date("Y-m-d H:i:s") . "] <br>";
    }
}

$test = new testClass();
$test->single();
// $test->bulk();
// $test->attach();
