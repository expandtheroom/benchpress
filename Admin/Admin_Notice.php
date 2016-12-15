<?php

namespace BenchPress\Admin;

/**
 * Create admin notices with the WP Admin. Admin Notices should be created by calling
 * the static ::create method.
 *
 * @package BenchPress\Admin
 */
class Admin_Notice {

    const WARNING = 0;
    const ERROR = 1;
    const SUCCESS = 2;

    /**
     * @param $type The type of notice. You can specify one the following types:
     *                Admin_Notice::WARNING, Admin_Notice::ERROR, Admin_Notice::SUCCESS.
     * @param $message The message to display.
     * @param bool $dismissible Whether you want this admin notice to be dismissible.
     */
    public static function create( $type, $message, $dismissible = false ) {
        $self = new static();

        $self->display_notice([
            'type' => $type,
            'message' => $message,
            'dismissible' => $dismissible
        ]);
    }

    private function display_notice( $notice ) {
        $classes = $this->get_classes_for_notice_type( $notice['type'] );
        $classes .= $notice['dismissible'] ? ' is-dismissible' : '';
        ?>
        <div class="<?php echo $classes; ?>" <?php if ( $notice['type'] == static::WARNING ): ?>style="border-left-color: #ffba00;"<?php endif; ?>>
            <p><?php echo $notice['message']; ?></p>
        </div>
        <?php
    }

    private function get_classes_for_notice_type( $notice_type ) {
        switch ( $notice_type ) {
            case self::WARNING:
                return 'notice notice-warning';
            case self::ERROR:
                return 'notice notice-error';
            case self::SUCCESS:
                return 'notice notice-success';
            default:
                return '';
        }
    }
}
