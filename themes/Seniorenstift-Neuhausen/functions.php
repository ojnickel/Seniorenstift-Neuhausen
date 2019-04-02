<?php
/**
 * Allgemeine Theme Funktionen
 *
 * @package CAB
 **/


 // ACF.
// require_once 'template-parts/acf-definitions.php';
// require_once 'template-parts/acf-functions.php';
// Breadcrumb.
// require_once 'template-parts/breadcrumb.php';
// HTML Maker laden.
require_once 'template-parts/html-maker.php';
// Kommentare deaktivieren.
require_once 'template-parts/disable-comments.php';
// Navigations-Funktionen bereitstellen.
require_once 'template-parts/nav-walkers.php';
// Funktionen für responsive Bilder.
require_once 'template-parts/picture-functions.php';
// Title Tag aktiveren.
add_theme_support( 'title-tag' );
// Beitragsbild aktiveren.
add_theme_support( 'post-thumbnails' );
// Bildgrößen registrieren.
add_image_size( 'tiles', 500, 304, true );
add_image_size( 'imageteaser', 369, 240, true );
add_image_size( 'video', 728, 650, true );
// add_image_size( 'hero-small', 640, 303 );
// add_image_size( 'hero-medium', 768, 363 );
// add_image_size( 'hero-large', 1024, 484 );
// add_image_size( 'hero-max', 1270, 600 );
add_filter( 'style_loader_src', 'ww_remove_ver_css_js', 9999, 2 );
add_filter( 'script_loader_src', 'ww_remove_ver_css_js', 9999, 2 );

/**
 * Entfernt die WP Versionsangaben
 *
 * @param str $src Pfad zur Datei.
 * @param str $handle Handle der Datei.
 */
function ww_remove_ver_css_js( $src, $handle ) {
	$handles_with_version = [ 'ww-script', 'ww-layout' ];

	if ( strpos( $src, 'ver=' ) && ! in_array( $handle, $handles_with_version, true ) ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}

// XML-RPC API für Fernzugriff deaktivieren.
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Emoji aus dem header entfernen
 **/
function ww_disable_emoji_dequeue_script() {
	wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'ww_disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Head Links entfernen
 **/
function ww_remove_headlinks() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_shortlink_header', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
}
add_action( 'init', 'ww_remove_headlinks' );

/**
 * Registriert die Menüs
 **/
function ww_register_menus() {
	register_nav_menu( 'main-menu', 'Hauptmenü' );
	register_nav_menu( 'social-menu', 'Socialmenü' );
	register_nav_menu( 'footer-menu', 'Footermenü' );
}
add_action( 'init', 'ww_register_menus' );

/**
 * Liefert die Body Klassen mit Menü-Index
 *
 * @param string  $menu_position Menü-Position des Hauptmenüs.
 * @param integer $post_id Beitrags-ID optional, Standard aktueller Post.
 */
function get_body_class_index( $menu_position, $post_id = false ) {

	$return    = '';
	$locations = get_nav_menu_locations();

	if ( array_key_exists( $menu_position, $locations ) ) {
		$menu       = wp_get_nav_menu_object( $locations[ $menu_position ] );
		$menu_items = wp_get_nav_menu_items( $menu->term_id );
		$post_id    = $post_id ? $post_id : get_the_ID();

		foreach ( $menu_items as $menu_item ) {
			if ( intval( $menu_item->object_id ) === $post_id ) {
				$return = 'p' . esc_attr( $menu_item->menu_order ) . ' ';
				break;
			}
		}
	}

	$return .= is_front_page() ? 'home' : basename( get_permalink() );

	return $return;

}

/**
 * Gibt die Body Klassen mit Menü-Index aus
 *
 * @param string  $menu_position Menü-Position des Hauptmenüs.
 * @param integer $post_id Beitrags-ID optional, Standard aktueller Post.
 */
function the_body_class_index( $menu_position, $post_id = false ) {

		echo esc_attr( get_body_class_index( $menu_position, $post_id = false ) );

}

/**
 * Liefert Array mit Datei inklusive Template Pfad und Änderungsdatum.
 *
 * @param array $src Pfad zur Datei innerhalb des WordPress Verzeichnisses.
 */
function get_src_path_uri_version( $src ) {
	$src      = '/' . rtrim( $src, '/' );
	$src_path = get_template_directory() . $src;
	$src_uri  = get_template_directory_uri() . $src;

	if ( file_exists( $src_path ) ) {
		return array(
			'uri'     => $src_uri,
			'version' => filemtime( $src_path ),
		);
	}

	return false;
}

/**
 * Ruft wp_enqueue_script setzt das Änderungsdatum der Datei als Version.
 *
 * @param array $handle    Name des Scripts.
 * @param array $src       Pfad zum Script innerhalb des aktuellen Template Verzeichnisses.
 * @param array $deps      Abhängigkeiten zu anderen Scripts.
 * @param array $in_footer True wenn Script vor /body statt vor /head ausgegeben werden soll, default false.
 */
function enqueue_script_with_timestamp( $handle, $src, $deps = array(), $in_footer = false ) {
	$src = get_src_path_uri_version( $src );

	if ( $src ) {
		wp_enqueue_script( $handle, $src['uri'], $deps, $src['version'], $in_footer );
	}
}

/**
 * Ruft wp_enqueue_style setzt das Änderungsdatum der Datei als Version.
 *
 * @param array $handle Name des Styles.
 * @param array $src    Pfad zum Style innerhalb des aktuellen Template Verzeichnisses.
 * @param array $deps   Abhängigkeiten zu anderen Styles.
 * @param array $media  Medien, für die das Style gedacht ist, default all.
 */
function enqueue_style_with_timestamp( $handle, $src, $deps = array(), $media = 'all' ) {
	$src = get_src_path_uri_version( $src );

	if ( $src ) {
		wp_enqueue_style( $handle, $src['uri'], $deps, $src['version'], $media );
	}
}

/**
 * Lädt Skripte und Styles.
 */
function enqueue_styles_scripts() {

	// WordPress jQuery entfernen.
	wp_deregister_script( 'jquery' );

	// Aktuelles jQuery (3.3.1) registrieren.
	wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), false, true );

	// Aktuelles jQuery laden.
	wp_enqueue_script( 'jquery' );

	// Haupt-Skript laden.
	enqueue_script_with_timestamp( 'ww-script', 'js/app.min.js', array( 'jquery' ), true );

	// Haupt-Style laden.
	if ( current_user_can( 'administrator' ) ) {
		enqueue_style_with_timestamp( 'ww-layout', 'css/template.css' );
	} else {
		enqueue_style_with_timestamp( 'ww-layout', 'css/template.min.css' );
	}

	wp_enqueue_style( 'typekit', '//use.typekit.net/bug8dtx.css' );

	// Slider laden.
	// wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), true );
	// SVG-Unterstützung für IE laden.
	wp_enqueue_script( 'svg4everybody', get_template_directory_uri() . '/js/svg4everybody.min.js', array( 'jquery' ), true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_styles_scripts' );

/**
 * Setzt die WP SEO Metabox nach unten.
 */
function filter_yoast_seo_metabox() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'filter_yoast_seo_metabox' );

/**
 * Entfernt die Admin Leiste für Abonnent.
 */
function ww_remove_admin_bar() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		show_admin_bar( false );
	}
}
add_action( 'after_setup_theme', 'ww_remove_admin_bar' );

/**
 * Deaktiviert den Admin Zugang für Abonnent.
 */
function ww_disable_dashboard() {

	$request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS );
	if (
		stripos( $request_uri, '/wp-admin/' ) !== false
		&&
		stripos( $request_uri, 'async-upload.php' ) === false
		&&
		stripos( $request_uri, 'admin-ajax.php' ) === false
		&&
		! current_user_can( 'edit_posts' )
	) {
		wp_redirect( home_url() );
		exit;
	}

}
add_action( 'admin_init', 'ww_disable_dashboard' );

function get_second_lang() {
	global $post;
	$post_id = $post->ID;
	if ( function_exists( 'pll_current_language' ) ) {
		$current_lang = pll_current_language( 'slug' );
		if ( 'ls' === $current_lang ) {
			$translated_post_id = pll_get_post( $post_id, 'de' );
			$icon               = 'language-active';
			$name               = 'Alltagssprache';
		} else {
			$translated_post_id = pll_get_post( $post_id, 'ls' );
			$icon               = 'book-active';
			$name               = 'Leichte Sprache';
		}
		$permalink = get_permalink( $translated_post_id );
	} else {
		$permalink = get_permalink( $post_id );
	}
	$content = '<div class="additional-info"><a href="' . esc_url( $permalink ) . '"><img src="' . esc_url( get_template_directory_uri() ) . '/img/icon-' . $icon . '.svg" alt=""><span>' . $name . '</span></a></div>';
	return $content;
}

if ( ! function_exists( 'write_log' ) ) {
	/**
	 * Schreibt individuellen Inhalt nach wp-content/debug.log
	 *
	 * @param misc $log Inhalt zur Ausgabe.
	 **/
	function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}

	}
}
