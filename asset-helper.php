<?php

class BPAssetHelper {

    public static function get_image($file) {
        return self::get_asset('img/' . $file);
    }

    public static function the_image($file) {
        echo self::get_image($file);
    }

    public static function get_js($file) {
        return self::get_asset('js/' . $file);
    }

    public static function the_js($file) {
        echo self::get_js($file);
    }

    public static function get_css($file) {
        return self::get_asset('css/' . $file);
    }

    public static function the_css($file) {
        echo self::get_css($file);
    }

    public static function get_asset($path) {
        return get_template_directory_uri() . '/' . BP_ASSET_DIR  . '/' . $path;
    }

    public static function the_asset($path) {
        echo self::get_asset($path);
    }
}