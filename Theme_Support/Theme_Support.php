<?php

namespace BenchPress\Theme_Support;

use BenchPress\Hooks\Base_Action;

class Theme_Support extends Base_Action {

    const LOGIN_LOGO = 'benchpress/login_logo';
    const CLEAN_UP = 'benchpress/clean_up';
    const REMOVE_ADMIN_MENUS = 'benchpress/remove_admin_menus';
    const GOOGLE_ANALYTICS = 'benchpress/google_analytics';
    const FAVICON = 'benchpress/favicon';

    protected function get_action() {
        return 'after_setup_theme';
    }

    protected function callback() {
        require_if_theme_supports( static::LOGIN_LOGO, __DIR__ . '/login-logo.php' );
        require_if_theme_supports( static::CLEAN_UP, __DIR__ . '/clean-up.php' );
        require_if_theme_supports( static::REMOVE_ADMIN_MENUS, __DIR__ . '/remove-admin-menus.php' );
        require_if_theme_supports( static::GOOGLE_ANALYTICS, __DIR__ . '/google-analytics.php' );
        require_if_theme_supports( static::FAVICON, __DIR__ . '/favicon.php' );
    }
}
