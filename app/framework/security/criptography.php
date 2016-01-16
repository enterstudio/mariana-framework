<?php namespace Mariana\Framework\Security;
use Mariana\Framework\Design\Singleton;

/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/15/2016
 * Time: 9:30 PM
 *
 * @Desc:
 *  Class for encrypting or hashing stuff
 *
 * @When:
 *      Encript when you want to access the results ( example - encript cookies so when you need to read them they are
 *      easily decrypted using the key.
 *
 *      Hash when you want the value be accessed only by comparison - example passwords.
 *
 * @ Singleton: Extends singleton because We dont have the need to use several different objects.
 */

class Criptography extends Singleton{

    private static $mode = 'MCRYPT_BLOWFISH';
    private static $key64 = "";

    public static function setKey(){
        if(static::$key64 == "") {
            static::$key64 = $_ENV["KEY64"];
        }
    }

    public static function encript($encrypt){
        #  Set the encription key
        self::setKey();

        #  Threat the key
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', static::$key64);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);

        return $encoded;
    }

    public static function decript($decrypt){
        #   Set the encription Key
        self::setKey();

        #   Get stuff out of it
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);

        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){
            return false;
        }

        $key = pack('H*', static::$key64);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calcmac!==$mac){
            return false;
        }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

    public static function hash($string, $method = false){
        if($method == false){
            $options = array(
                "cost"  =>  10
            );
            return password_hash($string, PASSWORD_BCRYPT, $options);
        }
        return password_hash($string, $method);
    }

    public static function compare($string, $hash ){
        return password_verify ( $string ,$hash );
    }


}