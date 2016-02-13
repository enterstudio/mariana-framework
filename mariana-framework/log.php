<?php
/**
 * 
 */

class Log {

    public static function store($filename, $logMessage,$userinfo=false,$mail = false) {

        # File name
        $filename = FILE_PATH.DS.'logs'.DS.$filename.'.log';

        # Extra stuff
        $extras = '';

        $datetime = date('Y-m-d H:i:s');

        # Open the handle
        $fd = fopen($filename, 'a');

        # Debug Backtrace
        $debugBacktrace = debug_backtrace();
        $line = $debugBacktrace[1]['line'];
        $file = $debugBacktrace[1]['file'];

        # Simple Message
        $message = preg_replace('/\s+/', ' ', trim($logMessage));
        $log = "\r\n".'DATE: ' . $datetime .' || ACTION: ' . $logMessage . ' || FILE AND LINE: ' . $file . ' - ' . $line;

        # If wants user info:
        if($userinfo) {
            $session = \Mariana\Framework\Session\Session::display();
            $cookie = \Mariana\Framework\Session\Cookie::display();

            if($session){
                $extras .= ' || SESSION: ' .$session;
            }
            if(isset($cookie)){
                $extras .= ' || COOKIES: ' .html_entity_decode($cookie);
            }

            $ip = self::getIp();
            $uid = self::getUniqueIdentifyer();
            $log = "\r\n".'DATE: ' . $datetime . ' || IP: ' . $ip . ' || UNIQUE: ' . $uid . ' || ACTION: ' . $logMessage . ' || FILE AND LINE: ' . $file . ' - ' . $line .' || EXTRA INFO: '.$extras;
        }


        fwrite($fd,$log);
        fclose($fd);

        if($mail){
            mail(Config::get('security-report-email-address'),$message,$log);
        }
    }

    private static function getIp(){
        switch(true){
            case (isset($_SERVER['HTTP_X_REAL_IP'])) :
                return htmlspecialchars($_SERVER['HTTP_X_REAL_IP']);
            case (isset($_SERVER['HTTP_CLIENT_IP'])) :
                return htmlspecialchars($_SERVER['HTTP_CLIENT_IP']);
            case (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) :
                return htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']);
            default :
                return htmlspecialchars($_SERVER['REMOTE_ADDR']);
        }
    }

    private static function getUniqueIdentifyer(){
        $uid = md5($_SERVER['HTTP_USER_AGENT'] .  $_SERVER['REMOTE_ADDR']);
        return $uid;
    }
}
?>

