<?php
/**
 * Plugin Name: Elementor Hosting Pricing
 * Description: A custom Elementor widget for displaying hosting plans with annual/monthly toggles
 * Version: 1.0.0
 * Author: Team Tipihost
 * Author URI: https://tipihost.com
 * Text Domain: elementor-hosting-pricing
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Main Elementor Hosting Pricing Class
 */
final class Elementor_Hosting_Pricing {

    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.4';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function i18n() {
        load_plugin_textdomain( 'elementor-hosting-pricing' );
    }

    public function init() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Add Plugin actions
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-hosting-pricing' ),
            '<strong>' . esc_html__( 'Elementor Hosting Pricing', 'elementor-hosting-pricing' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-hosting-pricing' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-hosting-pricing' ),
            '<strong>' . esc_html__( 'Elementor Hosting Pricing', 'elementor-hosting-pricing' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-hosting-pricing' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-hosting-pricing' ),
            '<strong>' . esc_html__( 'Elementor Hosting Pricing', 'elementor-hosting-pricing' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'elementor-hosting-pricing' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function init_widgets( $widgets_manager ) {
        // Include Widget files
        require_once( __DIR__ . '/widgets/hosting-pricing-widget.php' );

        // Register widget
        $widgets_manager->register( new \Elementor_Hosting_Pricing_Widget() );
    }

    public function register_scripts() {
        wp_register_script(
            'hosting-pricing-js',
            plugins_url( 'assets/js/hosting-pricing.js', __FILE__ ),
            ['jquery'],
            self::VERSION,
            true
        );
    }

    public function register_styles() {
        wp_register_style(
            'hosting-pricing-css',
            plugins_url( 'assets/css/hosting-pricing.css', __FILE__ ),
            [],
            self::VERSION
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'hosting-pricing-js' );
    }

    public function enqueue_styles() {
        wp_enqueue_style( 'hosting-pricing-css' );
    }
}

// Initialize the plugin
Elementor_Hosting_Pricing::instance();
