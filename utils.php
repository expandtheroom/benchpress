<?php

class BPUtils {

    public static function pr($mixed) {
        echo '<pre>'; print_r($mixed); '</pre>';
    }

    public static function prd($mixed) {
        self::pr($mixed); 
        die();
    }
}