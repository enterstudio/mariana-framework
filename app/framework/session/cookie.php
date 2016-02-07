<?php namespace Mariana\Framework\Session;

use Mariana\Framework\Config;

class Cookie {

    const Session = null;
    const OneDay = 86400;
    const SevenDays = 604800;
    const ThirtyDays = 2592000;
    const SixMonths = 15811200;
    const OneYear = 31536000;
    const Lifetime = -1; // 2030-01-01 00:00:00

    static public function clean($var){
        return htmlspecialchars($var);
    }

    static public function get($name, $default = '')
    {
        $name = self::clean($name);
        return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default);
    }

    static public function set($name, $value, $expiry = self::OneYear, $path = '/', $domain = false)
    {
        $name = self::clean($name);
        $value = self::clean($value);
        $retval = false;
        if (!headers_sent())
        {
            if ($domain === false)
                $domain = $_SERVER['HTTP_HOST'];

            if ($expiry === -1)
                $expiry = 1893456000; // Lifetime = 2030-01-01 00:00:00
            elseif (is_numeric($expiry))
                $expiry += time();
            else
                $expiry = strtotime($expiry);

            $retval = @setcookie($name, $value, $expiry, $path, $domain);
            if ($retval)
                $_COOKIE[$name] = $value;
        }
        return $retval;
    }

    static public function display(){
        if(isset($_COOKIE)) {
            return htmlspecialchars(json_encode($_COOKIE));
        }
        return false;
    }

    static public function delete($name, $path = '/', $domain = false, $remove_from_global = false)
    {
        $retval = false;
        if (!headers_sent())
        {
            if ($domain === false)
                $domain = $_SERVER['HTTP_HOST'];
            $retval = setcookie($name, '', time() - 3600, $path, $domain);

            if ($remove_from_global)
                unset($_COOKIE[$name]);
        }
        return $retval;
    }


}