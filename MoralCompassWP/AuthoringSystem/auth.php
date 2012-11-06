<?php
require_once(dirname(dirname(__FILE__)) . '/wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');


if ( is_user_logged_in() ) {
	global $current_user;
	get_currentuserinfo();
    echo 'Welcome, ' . $current_user->display_name;
} else {
	auth_redirect();
}
?>