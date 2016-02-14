<?php namespace Mariana\Framework\Time;


class Time{

    public function here($timezone = false,$timestamp = false, $format = false){
        /**
         * @param bool|false $timestamp
         * @param bool|false $timezone
         * @param bool|false $format
         *
         * @return DateTime from timestamp with chosen timezone and format;
         * @usage Time::here('Europe/Lisbon');
         */

        ($timestamp !== false)?: $timestamp = time();
        ($timezone !== false)?: $timezone = 'UTC';
        ($format !== false)?: $format = 'Y-m-d H:i:s';

        $dt = new \DateTime();
        $dt->setTimestamp($timestamp);

        //just for the fun: what would it be in UTC?
        $dt->setTimezone(new \DateTimeZone($timezone));

        //format the time
        $time = $dt->format($format);

        return $time;
    }


    public function difference($start, $end= false){
        /**
         * @param $start
         * @param $end
         * @return array
         *
         * @gets time difference in years, months, days, hours, minutes and seconds
         */

        ($end !== false)?: $end = time();

        $interval = $end - $start;

        return $interval;
    }

    public function humanDifference($difference){
        /**
         * @param $difference
         * @return string
         *
         * @desc time ago function php
         */
        $difference = ($difference<1)? 1 : $difference;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($difference < $unit) continue;
            $numberOfUnits = floor($difference / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

}