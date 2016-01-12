<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/12/2016
 * Time: 11:07 PM
 */


    /*  REMINDER - FUNCTIONS
     *  preg_match()
     *  preg_match_all()
     *  preg_replace()
     *  preg_replace_all()
     *  preg_split() //-> string to array
     *  preg_quote() //-> quotes regexp
     *
     *  REMINDER - SINTAX
     *  Placement:
     *      ^ //-> start of the stting
     *      $ //-> end of the string
     *  Count indicators:
     *      + //-> matches 1...n
     *      * //-> matches 0...n
     *      ? //-> matches 0 or 1
     *  Logical:
     *      | //-> or
     *      ^ //-> not
     *  Grouping:
     *      [] //-> match in group
     *
     *  Examples:
     *  [0-9/]      //-> matches digit or slash
     *  [a-z]       //-> matches any lowercase char
     *  [a-zA-Z]    //-> matches any char
     *  [aeiou]     //-> matches lowercase vowls
     *  [a-zA-Z0-9] //-> matches any char or number
     *  [\n\r\t\f]  //-> matches whitespaces
     *
     *  "/[-o-9a-ZA-Z.+_]+@[a-zA-Z]+\.[a-zA-Z]{2-4}" email regex
     */


//All major credit cards regex
$regex = '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})*$/';
//Alpha-numeric characters only
$regex = '/^[a-zA-Z0-9]*$/';
//Alpha-numeric characters with spaces only
$regex = '/^[a-zA-Z0-9 ]*$/';
//Alphabetic characters only
$regex = '/^[a-zA-Z]*$/';
//Amex credit card regex
$regex = '/^(3[47][0-9]{13})*$/';
//Australian Postal Codes
$regex = '/^((0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2}))*$/';
//Canadian Postal Codes
$regex = '/^([ABCEGHJKLMNPRSTVXY][0-9][A-Z] [0-9][A-Z][0-9])*$/';
//Canadian Provinces
$regex = '/^(?:AB|BC|MB|N[BLTSU]|ON|PE|QC|SK|YT)*$/';
//Date MM/DD/YYYY
$regex = '/^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2})*$/';
//Date YYYY/MM/DD
$regex = '#^((19|20)?[0-9]{2}[- /.](0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01]))*$#';
//Digits
$regex = '/^[0-9]*$/';
//Dinners Club
$regex = '/^(3(?:0[0-5]|[68][0-9])[0-9]{11})*$/';
//Email
$regex = '/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/';
//Ip
$regex = '/^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/';
//Digits
$regex = '/^[0-9]*$/';
//Lowercase letters
$regex = '/^([a-z])*$/';
//Master Card
$regex = '/^(5[1-5][0-9]{14})*$/';
//Phone Numbers (North American)
$regex = '/^((([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+)*$/';
//Passwords
$regex = '/^(?=^.{6,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/';
//Social Security Numbers US
$regex = '/^([0-9]{3}[-]*[0-9]{2}[-]*[0-9]{4})*$/';
//UK Postal Codes
$regex = '/^([A-Z]{1,2}[0-9][A-Z0-9]? [0-9][ABD-HJLNP-UW-Z]{2})*$/';
//Uppdercase characters
$regex = '/^([A-Z])*$/';
//URL
$regex = '/^(((http|https|ftp):\/\/)?([[a-zA-Z0-9]\-\.])+(\.)([[a-zA-Z0-9]]){2,4}([[a-zA-Z0-9]\/+=%&_\.~?\-]*))*$/';
//US STATES
$regex = '/^(?:A[KLRZ]|C[AOT]|D[CE]|FL|GA|HI|I[ADLN]|K[SY]|LA|M[ADEINOST]|N[CDEHJMVY]|O[HKR]|PA|RI|S[CD]|T[NX]|UT|V[AT]|W[AIVY])*$/';
//US ZIP CODES
$regex = '/^(?:A[KLRZ]|C[AOT]|D[CE]|FL|GA|HI|I[ADLN]|K[SY]|LA|M[ADEINOST]|N[CDEHJMVY]|O[HKR]|PA|RI|S[CD]|T[NX]|UT|V[AT]|W[AIVY])*$/';
//VISA Card
$regex = '/^(4[0-9]{12}(?:[0-9]{3})?)*$/';