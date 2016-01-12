<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 12/01/2016
 * Time: 10:03
 */

class email{

    private $smtpServer;                                //  The SMTP server you would be connecting to and using to send messages
    private $port;                                      //  The port that you would be using to connect to the SMTP server
    private $timeout;                                   //  Length of time (in seconds) that the script would try to connect before deciding it won't connect
    private $username;                                  //  Gmail Login
    private $password;                                  //  Gmail Password
    private $username_email;                            //  Email that recieves the emails ( example you can send by no-reply@mariana-framework.com as recieve in info@mariana-framework.com
    private $newline = "\r\n";                          //  Prrety self explanatory
    private $localdomain;                               //  Your website
    private $charset;                                   //  Encoding
    private $contentTransferEncoding = false;           //  If set to true, it would put in the header that the content is encoded (I believe... don't think it would actually encode the content

    // Do not change anything below
    private $smtpConnect = false;                       //  Connection Status
    private $to = false;                                //  Self Explanatory


    function __construct($to, $subject, $message, $to_admin) {

        // Mail configuration using the class
        $config = Config::get("email"); // app/config.php;

        $this->smtpServer = $config["smtp-server"];
        $this->port = $config["port"];
        $this->timeout = $config["timeout"];
        $this->username = $config["email-login"];
        $this->password = $config["email-password"];
        $this->username_email = $config["email-replyTo"];
        $this->charset = $config["charset"];
        $this->localdomain = $config["website"];

        $this->to = (($to_admin === true) ? $this->username_email : $to );
        $this->from = (($to_admin === false) ? $to : $this->username_email );

        $this->subject = &$subject;
        $this->message = &$message;

        // Connect to server
        if(!$this->smtpConnection()) {
            // Sacar erros
            echo '<pre>'.trim($this->Error).'</pre>'.$this->newline.'<!-- '.$this->newline;
            print_r($this->logArray);
            echo $this->newline.'-->'.$this->newline;
            return false;
        }

        return true;
    }


    private function smtpConnection(){

            // Connect to server
            $this->smtpConnect = fsockopen($this->smtpServer,$this->port,$errno,$error,$this->timeout);
            $this->logArray['CONNECT_RESPONSE'] = $this->readResponse();

            if (!is_resource($this->smtpConnect)) {
                return false;
            }
            $this->logArray['connection'] = "Connection accepted";
            // Hi, server!
            $this->sendCommand("HELLO {$this->localdomain}");
            $this->logArray['HELLO'] = $this->readResponse();
            // Let's know each other
            $this->sendCommand('AUTH LOGIN');
            $this->logArray['AUTH_REQUEST'] = $this->readResponse();
            // My name...
            $this->sendCommand(base64_encode($this->username));
            $this->logArray['REQUEST_USER'] = $this->readResponse();
            // My password..
            $this->sendCommand(base64_encode($this->password));
            $this->logArray['REQUEST_PASSWD'] = $this->readResponse();
            // If error in response auth...
            if (substr($this->logArray['REQUEST_PASSWD'],0,3)!='235') {
                $this->Error .= 'Authorization error! '.$this->logArray['REQUEST_PASSWD'].$this->newline;
                return false;
            }
            // "From" mail...
            $this->sendCommand("MAIL FROM: {$this->from}");
            $this->logArray['MAIL_FROM_RESPONSE'] = $this->readResponse();
            if (substr($this->logArray['MAIL_FROM_RESPONSE'],0,3)!='250') {
                $this->Error .= 'Mistake in sender\'s address! '.$this->logArray['MAIL_FROM_RESPONSE'].$this->newline;
                return false;
            }
            // "To" address
            $this->sendCommand("RCPT TO: {$this->to}");
            $this->logArray['RCPT_TO_RESPONCE'] = $this->readResponse();
            if(substr($this->logArray['RCPT_TO_RESPONCE'],0,3) != '250')
            {
                $this->Error .= 'Mistake in reciepent address! '.$this->logArray['RCPT_TO_RESPONCE'].$this->newline;
            }
            // Send data to server
            $this->sendCommand('DATA');
            $this->logArray['DATA_RESPONSE'] = $this->readResponse();
            // Send mail message
            if (!$this->sendMail()) return false;
            // Good bye server! =)
            $this->sendCommand('QUIT');
            $this->logArray['QUIT_RESPONSE'] = $this->readResponse();
            // Close smtp connect
            fclose($this->smtpConnect);
            return true;
    }

    function sendMail(){
            $this->sendHeaders();
            $this->sendCommand($this->message);
            $this->sendCommand('.');
            $this->logArray['SEND_DATA_RESPONSE'] = $this->readResponse();
            if(substr($this->logArray['SEND_DATA_RESPONSE'],0,3)!='250') {
                $this->Error .= 'Mistake in sending data! '.$this->logArray['SEND_DATA_RESPONSE'].$this->newline;
                return false;
            }
            return true;
    }

    private function readResponse() {
        $data="";
        while($str = fgets($this->smtpConnect,4096))
        {
            $data .= $str;
            if(substr($str,3,1) == " ") { break; }
        }
        return $data;
    }

    // function send command to server
    private function sendCommand($string) {
        fputs($this->smtpConnect,$string.$this->newline);
        return ;
    }

    private function sendHeaders() {
        $this->sendCommand("Date: ".date("D, j M Y G:i:s")." +0700");
        $this->sendCommand("From: <{$this->from}>");
        $this->sendCommand("Reply-To: <{$this->from}>");
        $this->sendCommand("To: <{$this->to}>");
        $this->sendCommand("Subject: {$this->subject}");
        $this->sendCommand("MIME-Version: 1.0");
        $this->sendCommand("Content-Type: text/html; charset={$this->charset}");
        if ($this->contentTransferEncoding) $this->sendCommand("Content-Transfer-Encoding: {$this->contentTransferEncoding}");
        return ;
    }

    function __destruct() {
        if (is_resource($this->smtpConnect)) fclose($this->smtpConnect);
    }


}


/*
 *
    if(new smtp_mail($to, $subject, $message, true))
    {
        echo 'Your message has being sent successfully.';
    }
    else
    {
        echo 'There was a problem sending the message';
        // Possibly log this for your reference
    }

    <?php
    // Checking if the form was really submitted
    if(isset($_POST['submit']))
    {
        // We need to initiate the error array
        $error = array();

        // Here we are checking if their name was filled in.
        if(empty($_POST['name']))
        {
            // The name field was left empty
            $error[] = "You need to enter a valid name.";
        }

        // Checking if a valid email address was filled in.
        if(!preg_match("/(?:[a-zA-Z0-9_\'\^\&\/\+\-])+(?:\.(?:[a-zA-Z0-9_\'\^\&\/\+\-])+)*@(?:(?:\[?(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))\.){3}(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\]?)|(?:[a-zA-Z0-9-]+\.)+(?:[a-zA-Z]){2,}\.?)/", $_POST['email']))
        {
            // An invalid email address was filled in.
            $error[] = "You need to enter a valid email address.";
        }

        // We need to determine the recipient of the message
        if(isset($_POST['to_admin']))
        {
            // Even if the to field was given a different email address, this would over-ride that address and be sent to admin.
            $to_admin = true;
            $to = false;
        }
        elseif(preg_match("/(?:[a-zA-Z0-9_\'\^\&\/\+\-])+(?:\.(?:[a-zA-Z0-9_\'\^\&\/\+\-])+)*@(?:(?:\[?(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))\.){3}(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\]?)|(?:[a-zA-Z0-9-]+\.)+(?:[a-zA-Z]){2,}\.?)/", $_POST['to']))
        {
            // If the process reaches here, then the message would be sent to the address put in the to field
            $to_admin = false;
            $to = $_POST['to'];
        }
        else
        {
            // There was an error... it's not sent to the admin OR to anyone...
            $error[] = "You need a valid recipient's (to) email address.";
        }

        // Now we need to validate the subject of the message
        if(strlen($_POST['subject']) < 5)
        {
            // The message is under 5 characters.
            $error[] = "The subject needs to be greater then 5 characters";
        }

        // Validating the actuall message
        if(strlen($_POST['message']) < 5)
        {
            $error[] = "The message needs to be greater then 5 characters";
        }

        // Counting the number of errors there was in the form submittion
        if(count($error) > 0)
        {
            // There were errors... we can't send a message with errors in the form submittion.

            // Looping through each error and providing a good list of errors for the user.
            $errors = "<ol>";
            foreach($error as $er)
            {
                $errors .= "<li>{$er}</li>\n";
            }
            $errors .= "</ol>";

            echo $errors;
        }
        else
        {
            // There were no errors, lets submit the actual message
            if(new smtp_mail($to, $_POST['subject'], $_POST['message'], $to_admin))
            {
                echo "The mail was sent successfully.";
            }
            else
            {
                echo "There was a problem sending the message.";
            }
        }
    }
    ?>


 */