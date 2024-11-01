<?php
/**
 * Plugin Name: TW Header & Footer Codes
 * Plugin URI:  https://www.themewarrior.com/item/tw-header-footer-codes/
 * Description: Add custom header and footer codes anywhere in your WordPress site.
 * Version:     1.0.4
 * Author:      ThemeWarrior
 * Author URI:  https://www.themewarrior.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tw-header-footer-codes
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define constants
define( 'TWHFC_VERSION', '1.0.4' );
define( 'TWHFC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TWHFC_TEMPLATE_DIR', TWHFC_PLUGIN_DIR . '/templates/' );

class TW_Header_Footer_Codes {

  	// Constructor
    public function __construct() {
        $this->basename = plugin_basename(__FILE__);

    	// Load functions
    	require_once( TWHFC_PLUGIN_DIR . '/includes/functions.php');

    	// Plugin settings
    	require_once( TWHFC_PLUGIN_DIR . '/includes/settings.php');

        // Load languages
        add_action( 'plugins_loaded', array($this, 'twhfc_load_languages') );

		// Add JS and CSS for admin screens
        add_action('admin_enqueue_scripts', array( $this, 'twhfc_enqueue_admin' ));

		// Add JS and CSS for frontend screens
        add_action('wp_enqueue_scripts', array( $this, 'twhfc_enqueue' ));

        // Add admin menu
        add_action( 'admin_menu', array( $this, 'twhfc_add_menu' ));

        add_filter( 'plugin_action_links_'.$this->basename, array($this, 'twhfc_settings_link') );

        // Register hooks
        register_activation_hook( __FILE__, array( $this, 'twhfc_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'twhfc_uninstall' ) );
    }

    /* function to add settings link */
    public function twhfc_settings_link( $links ) {
        $settings_link = '<a href="options-general.php?page=twhfc">'. esc_html__( 'Settings', 'tw-header-footer-codes' ) .'</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /*
      * Actions perform at loading of admin menu
      */
    public function twhfc_add_menu() {
		add_options_page(
            __( 'TW Header & Footer Codes', 'tw-header-footer-codes' ),
            __( 'TW Header & Footer Codes', 'tw-header-footer-codes' ),
            'manage_options',
            'twhfc',
            'twhfc_settings_page'
        );

        add_action( 'admin_init', 'twhfc_register_settings' );
    }

    public function twhfc_enqueue_admin($hook) {
        if ($hook != 'settings_page_twhfc') {
            return;
        }

        wp_enqueue_style('twhfc-admin-style', plugins_url('assets/css/admin-style.css', __FILE__));
    }

    // Enqueuing JS and a CSS files for use on the front end display
    public function twhfc_enqueue() {
    	wp_enqueue_style( 'dashicons' );
    }


    /*
     * Actions perform on loading of menu pages
     */
    public function twhfc_page_file_path() {

    }

    /*
     * Actions perform on activation of plugin
     */
    public function twhfc_install() {
		// do not generate any output here

        // Set default plugin setting values
        if ( empty( get_option('twhfc_options') ) ) {
            update_option( 'twhfc_options', array(
                'post_types' => array('page'),
                'header_codes' => '',
                'footer_codes' => '',
            ) );
        }
    }

    /*
     * Actions perform on de-activation of plugin
     */
    public function twhfc_uninstall() {

    }

    /* Function for internationalization */
    public function twhfc_load_languages() {
        load_plugin_textdomain( 'tw-header-footer-codes', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

}

new TW_Header_Footer_Codes();