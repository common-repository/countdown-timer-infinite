<?php
/*
Plugin Name: Countdown Timer Infinite
Plugin URI: https://github.com/Faridmia/countdown-timer-infinite
Description: A simple plugin that allows you to add a countdown timer to your website. In only a few minutes, you can design a beautiful and practical Countdown timer.
Version: 1.0.3
Author: Farid Mia
Author URI: https://profiles.wordpress.org/faridmia/
License: GPLv2 or later
Text Domain: countdown-infinite
Domain Path: /languages/
*/

if (!defined('ABSPATH')) {
	exit;
}

define('COUNTDOWNCDT_VERSION', '1.0.3');
define('COUNTDOWNCDT_CORE_URL', plugin_dir_url(__FILE__));
define('COUNTDOWNCDT_PLUGIN_ROOT', __FILE__);
define('COUNTDOWNCDT_PLUGIN_URL',  plugins_url('/', COUNTDOWNCDT_PLUGIN_ROOT));
define('COUNTDOWNCDT_PLUGIN_PATH', plugin_dir_path(COUNTDOWNCDT_PLUGIN_ROOT));
define('COUNTDOWNCDT_PLUGIN_BASE', plugin_basename(COUNTDOWNCDT_PLUGIN_ROOT));
define('COUNTDOWNCDT_CORE_ASSETS', COUNTDOWNCDT_CORE_URL);

require_once COUNTDOWNCDT_PLUGIN_PATH . '/includes/class-countdown-final.php';
require_once COUNTDOWNCDT_PLUGIN_PATH . '/classes/class-setting-tab-field.php';
require_once COUNTDOWNCDT_PLUGIN_PATH . '/includes/class-settings-callback.php';
require_once COUNTDOWNCDT_PLUGIN_PATH . '/includes/countdown-cdt-enqueue.php';

new Countdown_Cdt_Settings_Field();
