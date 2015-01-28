<?php

if(!defined('BENCHPRESS')) {
    define('BENCHPRESS', 1);
}

require(__DIR__ . '/config.php');
require(__DIR__ . '/utils.php');
require(__DIR__ . '/asset-helper.php');
require(__DIR__ . '/admin.php');
require(__DIR__ . '/helpers.php');
require(__DIR__ . '/partial.php');
require(__DIR__ . '/scripts.php');

require_if_theme_supports('bp-clean-up', __DIR__ . '/clean-up.php');
require_if_theme_supports('bp-login-logo', __DIR__ . '/login-logo.php');
require_if_theme_supports('bp-google-analytics', __DIR__ . '/google-analytics.php');
