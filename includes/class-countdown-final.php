<?php
/**
 * The main plugin class
 */
final class CountdownCdt_Infinite_Final {
    /**
     * Minimum PHP Version
     *
     * @since 1.2.0
     * @var   string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Constructor
     *
     * @since  1.2.0
     * @access public
     */
    /**
     * Widget Options
     *
     * @var widget_options
     */
    private function __construct() {
        // Register  deactive Active Hook
        register_activation_hook( COUNTDOWNCDT_PLUGIN_ROOT, [ $this, 'countdowncdt_activate'] );
        register_deactivation_hook( COUNTDOWNCDT_PLUGIN_ROOT, [ $this, 'countdowncdt_deactivate_hook'] );
        add_action( 'plugins_loaded', array( $this, 'countdowncdt_init_plugin' ) );

        add_shortcode( 'wpinf-countdown', array( $this, 'countdown_cdt_infin_shortcode' ) );

        add_filter( 'plugin_action_links_' . COUNTDOWNCDT_PLUGIN_BASE, [ $this, 'countdown_cdt_setting_page_link_func'] );
    }

    /**
     * Check if a plugin is installed
     *
     * @since v1.0.0
     */
    public function is_plugin_installed( $basename ) {
        if ( !function_exists( 'get_plugins' ) ) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        $installed_plugins = get_plugins();

        return isset( $installed_plugins[$basename] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \init
     */
    public static function init() {
        static $instance = false;

        if ( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function countdowncdt_init_plugin() {
        $this->check_php_version();
        load_plugin_textdomain( 'countdown-infinite', false, basename( dirname( COUNTDOWNCDT_PLUGIN_ROOT ) ) . '/languages' );

    }

    public function check_php_version() {
        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'countdowncdt_admin_notice_min_php_version' ) );
            return;
        }
    }

    // plugin activate function
    public function countdowncdt_is_plugins_active( $plugin_file_path = NULL ) {
        $all_plugins_list = get_plugins();
        return isset( $all_plugins_list[ $plugin_file_path ] );
    }
    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since  1.0.0
     * @access public
     */
    public function countdowncdt_admin_notice_min_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'countdown-infinite' ),
            '<strong>' . esc_html__( 'Countdown Timer Infinite', 'countdown-infinite' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'countdown-infinite' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function countdowncdt_activate() {
        $installed = get_option( 'countdowncdt_timer_installed' );

        if ( !$installed ) {
            update_option( 'countdowncdt_timer_installed', time() );
        }

    }

    /**
     * deactive the main plugin
     *
     * @return \countdowncdt_deactivate_hook
     */
    public function countdowncdt_deactivate_hook() {
        flush_rewrite_rules();
    }

    // countdown shortcode func

    public function countdown_cdt_infin_shortcode( $arguments,$content = null ) {

        $cdt_inf_basics  = get_option( "cdt_inf_basics" );
        $layout_style     = $cdt_inf_basics['layout_style'];
        $cdt_heading     = $cdt_inf_basics['countdown_heading'];
        $heading_on_off  = $cdt_inf_basics['heading_on_off'];

        $defaults = array(
            'heading_title'  => $cdt_heading,
            'countdown_date' => '06-30-2023',
        );

        $attributes     = shortcode_atts( $defaults, $arguments );
        $cdt_date        = $cdt_inf_basics['cdt_date'];
        if(!empty($cdt_date)) {
            $cdt_date2        = $cdt_inf_basics['cdt_date'];

            $timestamp = strtotime($cdt_date2);
            // Creating new date format from that timestamp
            $new_date = date("m-d-Y", $timestamp);

        } else {
            $new_date = $attributes['countdown_date'];
        }
        $output_heading = '';
        if ( $heading_on_off == 'on' ):
            $output_heading .= "<h2 class='infinite-cdt-title'>{$attributes['heading_title']}</h2>";
        endif;
        $randid           = rand( 10, 1000 );
        $shortcode_output = '';
        if($layout_style == '1') {
            $shortcode_output .= "<div class='infinite-cdt-wrapper'>
            ".wp_kses_post($output_heading)."
                <div class='countdown-infinite-item' data-id='countdown-infinite-item-".esc_attr($randid)."' data-date='".$new_date."'>
                    <div id='countdown-infinite-item-".esc_attr($randid)."'></div>
                </div>
            </div>";
        } elseif($layout_style == '2') {
            $shortcode_output .= "<div class='cdt-inf-banner-text text-center' style='text-align:center;'>
            <div id='countdown' data-cdtdate='".$new_date."'></div>
        </div>";
        }

        return $shortcode_output;
    }

    public function countdown_cdt_setting_page_link_func( $links ) {
        $action_link = sprintf( "<a href='%s'>%s</a>", admin_url( 'options-general.php?page=countdown_timer_cdt' ), __( 'Settings', 'countdown-infinite' ) );
        array_push( $links, $action_link );
        return $links;
    }
}
/**
 * Initializes the main plugin
 *
 * @return \COUNTDOWNCDT
 */
function countdown_cdt_init() {
    return CountdownCdt_Infinite_Final::init();
}

// kick-off the plugin
countdown_cdt_init();
?>