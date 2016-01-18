<?php namespace Mariana\Framework\Session;
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 17/01/2016
 * Time: 11:43
 */
use Mariana\Framework\Session\Cookie;
use Mariana\Framework\Config;

class LifeTime extends Session{

    public static function generate_random_128_256_bit() {
        $rand = rand (0, 1);
        ($rand == 1)? $rand = 16 : $rand = 32;
        return bin2hex(random_bytes($rand));
    }

    public static function rememberMe(/* Users */ $user){
        $token = self::generate_random_128_256_bit(); // generate a token, should be 128 - 256 bit
        //$u->token = $token;
        //$u->save();
        $cookie = $user . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, getenv("key"));
        $cookie .= ':' . $mac;
        Cookie::set("rememberme",$cookie);
    }

    public static function validate(/*Users*/ $user){
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
        if ($cookie) {
            list ($user, $token, $mac) = explode(':', $cookie);
            if (self::timingSafeCompare(hash_hmac('sha256', $user . ':' . $token, getenv("key")), $mac)) {
                return false;
            }
            $usertoken = $user->token;
            if (self::timingSafeCompare($usertoken, $token)) {
                //Login again.
            }
        }
    }

    /**
     * A timing safe equals comparison
     *
     * To prevent leaking length information, it is important
     * that user input is always used as the second parameter.
     *
     * @param string $safe The internal (safe) value to be checked
     * @param string $user The user submitted (unsafe) value
     *
     * @return boolean True if the two strings are identical.
     */
    public static function timingSafeCompare($safe, $user) {
        // Prevent issues if string length is 0
        $safe .= chr(0);
        $user .= chr(0);

        $safeLen = strlen($safe);
        $userLen = strlen($user);

        // Set the result to the difference between the lengths
        $result = $safeLen - $userLen;

        // Note that we ALWAYS iterate over the user-supplied length
        // This is to prevent leaking length information
        for ($i = 0; $i < $userLen; $i++) {
            // Using % here is a trick to prevent notices
            // It's safe, since if the lengths are different
            // $result is already non-0
            $result |= (ord($safe[$i % $safeLen]) ^ ord($user[$i]));
        }

        // They are only identical strings if $result is exactly 0...
        return $result === 0;
    }

}