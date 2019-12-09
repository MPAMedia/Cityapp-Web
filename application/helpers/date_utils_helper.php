<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 3/3/2018
 * Time: 13:27
 */


class MyDateUtils{




    public static function convert($time, $from_defaultTZ="UTC", $to_newTimeTZ, $schema="Y-m-d H:i:s"){

        if($from_defaultTZ==$to_newTimeTZ){
            $changetime = new DateTime($time, new DateTimeZone($from_defaultTZ));
            return $changetime->format($schema);
        }

        if(!in_array($from_defaultTZ, DateTimeZone::listIdentifiers(DateTimeZone::ALL))){
            return $time;
        }
        if(!in_array($from_defaultTZ, DateTimeZone::listIdentifiers(DateTimeZone::ALL))){
            return $time;
        }


        $changetime = new DateTime($time, new DateTimeZone($from_defaultTZ));
        $changetime->setTimezone(new DateTimeZone($to_newTimeTZ));
        return $changetime->format($schema);

    }


    public static function format_interval(DateInterval $interval) {
        $result = "";
        if ($interval->y) { $result .= $interval->format("%y")." ".Translate::sprint("years")." "; }
        if ($interval->m) { $result .= $interval->format("%m")." ".Translate::sprint("months")." "; }
        if ($interval->d) { $result .= $interval->format("%d ")." ".Translate::sprint("days")." "; }
        if ($interval->h) { $result .= $interval->format("%h")." ".Translate::sprint("hours")." "; }
        if ($interval->i) { $result .= $interval->format("%i")." ".Translate::sprint("minutes")." "; }
        if ($interval->s) { $result .= $interval->format("%s")." ".Translate::sprint("seconds")." "; }

        return $result;
    }


    public static function diff_days(DateInterval $interval) {


    }


    public static function convert_months(DateInterval $interval) {
        $result = "";
        if ($interval->m) { $result .= $interval->format("%m")." ".Translate::sprint("months")." "; }
        return $result;
    }


    public static function convert_days(DateInterval $interval) {
        $result = "";
        if ($interval->d) { $result .= $interval->format("%d ")." ".Translate::sprint("days")." "; }
        return $result;
    }



}