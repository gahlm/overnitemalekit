<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');


class ModularSite extends TimberSite {
	function __construct() {
		show_admin_bar( false );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_filter( 'timber_context', array($this, 'add_to_context' ) );
		add_filter( 'get_twig', array($this, 'add_to_twig' ) ); //hook into twig global object
		parent::__construct();
	}

	function add_to_context( $context ) {
		$context['header_nav_menu'] = new TimberMenu('header_nav');
		$context['footer_nav_menu'] = new TimberMenu('footer_nav');
		$context['copy_nav_menu'] = new TimberMenu('footer_copy_nav');
		$context['site'] = $this;
		$context['options'] = get_fields('options');


		$all_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'orderby' => array(
			    'date' => 'DESC'
			)
		);
		$context['all_posts'] = Timber::get_posts( $all_posts_args );

		
		$post_taxonomies_args = array(
		    'taxonomy' => 'category',
		    'hide_empty' => false,
		);
		$context['post_taxonomies'] = get_terms( $post_taxonomies_args );

		
		return $context;
	}

	function add_to_twig( $twig ) {
		$twig->addFilter(new Twig_SimpleFilter('format_phone_number', array($this, 'format_phone_number')));
		return $twig;
	}

	function format_phone_number( $number ) {
		$formattedNumber = sprintf("%s.%s.%s",
			substr($number, 0, 3),
			substr($number, 3, 3),
			substr($number, 6, 4));

		return $formattedNumber;
	}
}

new ModularSite();

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	
}

function app_scripts() {
	wp_enqueue_script('jquery');
  wp_enqueue_style('app-css', (get_template_directory_uri() . "/dist/application.css"), null, null);
  // wp_enqueue_script('app-js', (get_template_directory_uri() . "/dist/application.js"), ['jquery'], null, true);
}

add_action('wp_enqueue_scripts', 'app_scripts');



function app_get_types() {
    require_once(__DIR__ . '/types/post-types.php');
}

add_action('init', 'app_get_types');
