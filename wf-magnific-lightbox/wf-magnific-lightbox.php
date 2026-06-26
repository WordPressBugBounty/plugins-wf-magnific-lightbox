<?php
	/*
	Plugin Name: WF Magnific Lightbox
	Plugin URI: http://www.wunderfarm.com/plugins/wf-magnific-lightbox
	Description: WF Magnific Lightbox is the `wunderfarm-way` to show your images with wordpress in a truly responsive lightbox.
	Version: 0.9.14
	License: GNU General Public License v2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	Author: wunderfarm
	Text Domain: wf-magnific-lightbox
	Domain Path: /languages
	Author URI: http://www.wunderfarm.com
	*/

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	define( 'WF_MLIGHTBOX_VERSION', '0.9.14' );

	// Translations for plugins hosted on WordPress.org are loaded automatically since WP 4.6.

	include('includes/utils.php'); // Load utils include.
	include('includes/wf_mlightbox_shortcode.php'); // Override the WordPress gallery shortcode.
	include('includes/wf_mlightbox_settings.php'); // Load settings page.

/*
* Enqueue JS and CSS
*/

function wf_mlightbox_scripts() {
	// Load CSS
	wp_enqueue_style( 'wf_magnific_popup_styles', plugins_url( '/css/magnific-popup.css', __FILE__ ), array(), WF_MLIGHTBOX_VERSION );
	wp_enqueue_style( 'wfml_custom', plugins_url( '/css/wfml-custom.css', __FILE__ ), array( 'wf_magnific_popup_styles'), WF_MLIGHTBOX_VERSION );
	// Load JS
	wp_enqueue_script( 'wf_magnific_popup_scripts', plugins_url( '/js/jquery.magnific-popup.min.1.0.1.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'wfml_init', plugins_url( '/js/wfml-init.js', __FILE__ ), array( 'jquery', 'wf_magnific_popup_scripts' ), WF_MLIGHTBOX_VERSION, true );

	if (function_exists('wf_get_language') && wf_get_language()) {
		wp_localize_script('wfml_init', 'WfmlOptions', array('lang'=>wf_get_language()));
	}
}

add_action( 'wp_enqueue_scripts', 'wf_mlightbox_scripts' );

?>
