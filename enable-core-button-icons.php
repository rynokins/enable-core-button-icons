<?php
/**
 * Plugin Name:         Enable Core Button Icons
 * Plugin URI:          https://github.com/rynokins/enable-core-button-icons
 * Description:         Easily add icons to core Button blocks.
 * Version:             0.1.0
 * Requires at least:   6.3
 * Requires PHP:        7.4
 * Author:              Ryan Edwards (forked from https://github.com/ndiego/enable-button-icons)
 * Author URI:          https://github.com/rynokins
 * License:             GPLv2
 * License URI:         https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:         enable-core-button-icons
 * Domain Path:         /languages
 *
 * @package enable-core-button-icons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue Editor scripts and styles.
 */
function enable_button_icons_enqueue_block_editor_assets()
{
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
function enqueue_button_icons_block_editor_styles()
{
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
function enable_button_icons_block_styles()
{
	wp_enqueue_block_style( 'core/button', [
		'handle' => 'enable-core-button-icons-block-styles',
		'src'    => plugin_dir_url( __FILE__ ) . 'build/style.css',
		'ver'    => wp_get_theme()->get( 'Version' ),
		'path'   => plugin_dir_path( __FILE__ ) . 'build/style.css',
	]);
}
add_action( 'init', 'enable_button_icons_block_styles' );

/**
 * Preload assets
 */
function preload_core_button_icons_assets()
{
	// Loader
	echo '<link rel="modulepreload" href="' . plugin_dir_url( __FILE__ ) . 'enable-core-button-icons-svg-loader.js' . '" />';

	// Fetch worker
	echo '<link rel="modulepreload" href="' . plugin_dir_url( __FILE__ ) . 'fetchWorker.js' . '" />';

	// SVG sprite
	echo '<link rel="preload" href="' . plugin_dir_path( __FILE__ ) .'node_modules/feather-icons/dist/feather-sprite.svg";' . '" as="image" />';
};
add_action('wp_head', 'preload_core_button_icons_assets');


/**
 * Enqueue block styles
 * (Applies to both frontend and Editor)
 */
function enable_button_icons_svg_loader()
{
	// use asset file's version number
	$asset_file  = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	wp_enqueue_script_module(
		'enable-button-icons-svg-loader',
		plugin_dir_url( __FILE__ ) . 'enable-core-button-icons-svg-loader.js', [],
		$asset_file['version']
	);
}
add_action( 'wp_enqueue_scripts', 'enable_button_icons_svg_loader' );

/**
 * Render icons on the frontend.
 */
function enable_button_icons_render_block_button( $block_content, $block )
{
	if ( ! isset( $block['attrs']['icon'] ) ) return $block_content;

	$icon         = $block['attrs']['icon'];
	$positionLeft = isset( $block['attrs']['iconPositionLeft'] ) ? $block['attrs']['iconPositionLeft'] : false;
	$iconOnly = isset( $block['attrs']['emptyContent'] ) ? 'is-icon-only' : '';


	// Append the icon class to the block.
	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag() ) {
		$p->add_class( 'has-icon__' . $icon );
		$p->add_class( $iconOnly );

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