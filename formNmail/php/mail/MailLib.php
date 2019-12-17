<?php
    require 'mailClasses/vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class MailLib {
        public function __construct() {
            $this->init();
        }

        private function init() {
            $this->mail = new PHPMailer(true);

            try {
                $this->mail->SMTPDebug = 0;                                       // Enable verbose debug output
                $this->mail->Debugoutput = 'html';
                $this->mail->FromName = 'no-reply@noombla.nl';
                // $this->mail->SMTPAutoTLS = false;

                $this->mail->isSMTP();                                            // Set mailer to use SMTP
                $this->mail->Host       = 'host';                                 // Specify main and backup SMTP servers
                $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $this->mail->Username   = 'username';                             // SMTP username
                $this->mail->From       = 'no-reply@noombla.nl';
                $this->mail->Password   = 'pass';                                 // SMTP password
                $this->mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
                $this->mail->Port       = 465;

                // ini_set('SMTP', $this->mail->Host);
                // ini_set('smtp_port', $this->mail->Port);
                if ( $this->mail->IsError() ) { // ADDED - This error checking was missing
                    return FALSE;
                }
                else {
                    return TRUE;
                }

            } catch (Exception $e) {
                echo "There has been an error initializing PHPMailer: {$this->mail->ErrorInfo}";
            }
        }

        public function regularMail($to, $subject, $message, $reply=NULL) {
            try {
                $this->mail->addAddress($to);               // Name is optional

                if ($reply) {
                    $this->mail->addReplyTo($reply);
                }

                // Content
                $this->mail->isHTML(false);                                  // Set email format to HTML
                $this->mail->Subject = $subject;
                $this->mail->Body    = $message;

                $this->mail->send();
                $this->mail->SmtpClose();
                // echo 'Message has been sent';
            } catch (Exception $e) {
                // echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            }

            if ( $this->mail->IsError() ) { // ADDED - This error checking was missing
                return FALSE;
            }
            else {
                return TRUE;
            }
        }

        public function attachmentMail($to, $subject, $message, $attach1, $attach2=NULL) {
            try {
                $this->mail->addAttachment($attach1);         // Add attachments

                if ($attach2) {
                    $this->mail->addAttachment($attach2);
                }

                $this->regularMail($to, $subject, $message);
            } catch (Exception $e) {
                echo "Problem setting up attachment Mailer error: {$this->mail->ErrorInfo}";
            }

            if ( $this->mail->IsError() ) { // ADDED - This error checking was missing
                return FALSE;
            }
            else {
                return TRUE;
            }
        }

        public function regularBulk($adressArray, $subject, $message) {
            for ($i=0; $i < count($adressArray); $i++) {
                $this->init();
                $this->regularMail($adressArray[$i], $subject, $message);
            }

            if ( $this->mail->IsError() ) { // ADDED - This error checking was missing
                return FALSE;
            }
            else {
                return TRUE;
            }
        }

    }

 ?>
