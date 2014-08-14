<?php

class BPAssetHelper {

    public static function get_image($file) {
        return self::get_asset('img/' . $file);
    }

    public static function the_image($file) {
        echo self::get_image($file);
    }

    public static function get_asset($path) {
        return get_template_directory_uri() . '/assets/' . $path;
    }

    public static function the_asset($path) {
        echo self::get_asset($path);
    }
}