<?php namespace Mariana\Framework\Email;
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 12/01/2016
 * Time: 10:03
 */

use Mariana\Framework\Config;

class Email
{

    private $SmtpServer;
    private $SmtpUser;
    private $SmtpPass;
    private $SmtpPort;
    private $from;
    private $to;
    private $subject;
    private $body;

    private function config($from, $to, $subject, $body)
    {

        $this->SmtpServer = Config::get("email")["smtp-server"];
        $this->SmtpUser = base64_encode(Config::get("email")["email-login"]);
        $this->SmtpPass = base64_encode(Config::get("email")["email-password"]);
        $this->SmtpPort = Config::get("email")["port"];
        $this->from = $from;
        $this->to = $to;

        $this->subject = $subject;
        $this->body = $body;

    }


    public function SendMail($from, $to, $subject,$body)
    {
        $this->config($from,$to,$subject,$body);

        if ($SMTPIN = fsockopen($this->SmtpServer, $this->SmtpPort)) {

            fputs($SMTPIN, "EHLO " . $this->SmtpServer . "\r\n");
            $talk["hello"] = fgets($SMTPIN, 1024);

            fputs($SMTPIN, "auth login\r\n");
            $talk["res"] = fgets($SMTPIN, 1024);
            fputs($SMTPIN, $this->SmtpUser . "\r\n");
            $talk["user"] = fgets($SMTPIN, 1024);

            fputs($SMTPIN, $this->SmtpPass . "\r\n");
            $talk["pass"] = fgets($SMTPIN, 256);

            fputs($SMTPIN, "MAIL FROM: <" . $this->from . ">\r\n");
            $talk["From"] = fgets($SMTPIN, 1024);
            fputs($SMTPIN, "RCPT TO: <" . $this->to . ">\r\n");
            $talk["To"] = fgets($SMTPIN, 1024);

            fputs($SMTPIN, "DATA\r\n");
            $talk["data"] = fgets($SMTPIN, 1024);


            fputs($SMTPIN, "To: <" . $this->to . ">\r\nFrom: <" . $this->from . ">\r\nSubject:" . $this->subject . "\r\n\r\n\r\n" . $this->body . "\r\n.\r\n");
            $talk["send"] = fgets($SMTPIN, 256);

            //CLOSE CONNECTION AND EXIT ... 

            fputs($SMTPIN, "QUIT\r\n");
            fclose($SMTPIN);
            //  
        }

        return $talk;


    }

}