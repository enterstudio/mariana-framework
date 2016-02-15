<?php namespace Mariana\Framework\Validation;


use Mariana\Framework\Session\Flash;

class Validation{


    private static $errors = array();
    private static $error_count = 0;
    private static $regexes = array(
        'date' => "^[0-9]{4}[-/][0-9]{1,2}[-/][0-9]{1,2}\$",
        'amount' => "^[-]?[0-9]+\$",
        'number' => "^[-]?[0-9,]+\$",
        'alfanum' => "^[0-9a-zA-Z ,.-_\\s\?\!]+\$",
        'not_empty' => "[a-z0-9A-Z]+",
        'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
        'phone' => "^[0-9]{10,11}\$",
        'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
        'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
        'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
        '2digitopt' => "^\d+(\,\d{2})?\$",
        '2digitforce' => "^\d+\,\d\d\$",
        'anything' => "^[\d\D]{1,}\$",
        'username' => "^[\w]{3,32}\$",
    );
    private static $valid_validations = array(
        'date',
        'amount',
        'number',
        'alfanum',
        'required',
        'not_empty',
        'words',
        'phone',
        'zipcode',
        'plate',
        'price',
        '2digitopt',
        '2digitforce',
        'anything',
        'username',
        'min',
        'max',
        'ip',
        'email',
        'bool',
        'url',
        'unique',
        'matches'
    );

    public static function setError($error){
        self::$error_count++;
        array_push(self::$errors, $error);
        return true;
    }

    public static function checkRequest($key){
        if(isset($_POST[$key])){
            return $_POST[$key];
        }elseif(isset($_GET[$key])){
            return $_GET[$key];
        }else{
            return false;
        }
    }

    public static function validateItem($var, $type, $value)
    {
        #check if it's valid validation
        if(!in_array($type,self::$valid_validations)) {
            if ($type !== 'name') {
                self::setError(strtoupper($type) . ' is not an allowed validation, please choose one of the following: ' . implode(',', self::$valid_validations));
            }else{
                return true;
            }
        }

        if(array_key_exists($type, self::$regexes))
        {
            $returnval =  filter_var($var, FILTER_VALIDATE_REGEXP, array(
                "options"=> array("regexp"=>'!'.self::$regexes[$type].'!i'))
                ) !== false;
            return($returnval);
        }

        $filter = false;
        switch($type)
        {
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;
                break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
                break;
            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
                break;
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
                break;
            case 'min':
                (strlen($type) < $value)?
                    $filter = false:
                    $filter = true;
                break;
            case 'max':
                (strlen($type) > $value)?
                    $filter = false:
                    $filter = true;
                break;
            case 'required':
                (empty($value) || $value == false)?
                    $filter = false:
                    $filter = true;
                break;
            case 'matches':
                ($var == self::checkRequest($value) && self::checkRequest($value) !== false) ?
                    $filter = true :
                    $filter = false;
                break;
        }
        if($filter === false){
            return false;
        }elseif($filter === true) {
            return true;
        }else{

            return (filter_var($var, $filter) !== false) ?
                true :
                false;
        }

    }

    public static function check(Array $items = array()){
        foreach($items as $key => $item){
            $request = self::checkRequest($key);
            (isset($item['name']) && $item['name'])?
                $name = $item['name']:
                $name = $key;

            foreach($item as $rule => $value){

                if($request){
                    if($rule !== 'name'){
                        (self::validateItem($request,$rule,$value) !== false)?:
                            self::setError("Invalid value in the following input: ".$rule);
                    }
                }
            }
        }
        if(self::$error_count > 0){
            Flash::setMessages(self::$errors);
            return false;
        }else{
            return true;
        }
    }

}
