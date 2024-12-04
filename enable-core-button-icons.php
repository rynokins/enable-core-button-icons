<?php
/**
 * Plugin Name:         Enable Core Button Icons
 * Plugin URI:          https://github.com/rynokins/enable-core-button-icons
 * Description:         Easily add icons to core Button blocks.
 * Version:             0.1.0
 * Requires at least:   6.3
 * Requires PHP:        7.4
 * Author:              Ryan Edwards
 * Author URI:          https://github.com/rynokins
 * License:             GPLv2
 * License URI:         https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:         enable-core-button-icons
 * Domain Path:         /languages
 *
 * @package enable-core-button-icons
 */

defined( 'ABSPATH' ) || exit;

use WP_HTML_Tag_Processor;

/**
 * Enqueue Editor scripts and styles.
 */
function enable_button_icons_enqueue_block_editor_assets() {
	$asset_file  = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	wp_enqueue_script(
		'enable-core-button-icons-editor-scripts',
		plugin_dir_url( __FILE__ ) . 'build/index.js',
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_set_script_translations(
		'enable-core-button-icons-editor-scripts',
		'enable-core-button-icons',
		plugin_dir_path( __FILE__ ) . 'languages'
	);
}
add_action( 'enqueue_block_editor_assets', 'enable_button_icons_enqueue_block_editor_assets' );

/**
 * Enqueue block Editor styles
 */
function enqueue_button_icons_block_editor_styles() {
	wp_enqueue_style(
		'enable-core-button-icons-editor-styles',
		plugin_dir_url( __FILE__ ) . 'build/editor.css'
	);
}
add_action( 'admin_enqueue_scripts', 'enqueue_button_icons_block_editor_styles' );

/**
 * Enqueue block styles
 * (Applies to both frontend and Editor)
 */
function enable_button_icons_block_styles() {
	wp_enqueue_block_style(
		'core/button',
		array(
			'handle' => 'enable-core-button-icons-block-styles',
			'src'    => plugin_dir_url( __FILE__ ) . 'build/style.css',
			'ver'    => wp_get_theme()->get( 'Version' ),
			'path'   => plugin_dir_path( __FILE__ ) . 'build/style.css',
		)
	);
}
add_action( 'init', 'enable_button_icons_block_styles' );

/**
 * Load feather-icons svg after body open
	*/
function embed_feather_icons_svg() {
	if ( is_admin() ) return;

	echo '<span class="feather-icon-library">' . file_get_contents( get_template_directory_uri() . '/node_modules/feather-icons/dist/feather-sprite.svg' ) . '</span>';

}
add_action( 'wp_body_open', 'embed_feather_icons_svg' );

/**
 * Render icons on the frontend.
 */
function enable_button_icons_render_block_button( $block_content, $block ) {
	if ( ! isset( $block['attrs']['icon'] ) ) {
		return $block_content;
	}

	$icon         = $block['attrs']['icon'];
	$positionLeft = isset( $block['attrs']['iconPositionLeft'] ) ? $block['attrs']['iconPositionLeft'] : false;

	// Append the icon class to the block.
	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag() ) {
		$p->add_class( 'has-icon__' . $icon );
	}
	$block_content = $p->get_updated_html();

	// Add the SVG icon either to the left of right of the button text.
	//
	$block_content = $positionLeft
		? preg_replace( '/(<a[^>]*>)(.*?)(<\/a>)/i', '$1<svg xmlns="http://www.w3.org/2000/svg" class="wp-block-button__link-icon"><use xlink:href="#'. $icon . '"></use></svg>$2$3', $block_content )
		: preg_replace( '/(<a[^>]*>)(.*?)(<\/a>)/i', '$1$2<svg xmlns="http://www.w3.org/2000/svg" class="wp-block-button__link-icon"><use xlink:href="#'. $icon . '"></use></svg>$3', $block_content );

	return $block_content;
}
add_filter( 'render_block_core/button', 'enable_button_icons_render_block_button', 10, 2 );
