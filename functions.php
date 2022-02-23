<?php

// Handle no Timber plugin case
if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}


// Let Timber know where Twig directories are located
Timber::$dirname = array('templates', 'views');


// Initialize Timber
class ModularSite extends TimberSite {
	function __construct() {
		show_admin_bar( false );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
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

		$history = array(
			// Get post type project
			'post_type' => 'event',
			// Get all posts
			'posts_per_page' => -1,
			// Order by post date
			'meta_key' => 'year',
			'orderby'	=> 'meta_value',
			'order' => 'ASC'
		);
		$context['history'] = Timber::get_posts( $history );


		$facilities = array(
			// Get post type project
			'post_type' => 'Facility',
			// Get all posts
			'posts_per_page' => -1,
			'meta_key' => 'facility_name',
			'orderby'	=> 'meta_value',
			'order' => 'ASC'
			// 'orderby' => array(
			//     'title&order' => 'ASC'
			// )
		);
		$context['facilities'] = Timber::get_posts( $facilities );


		$args = array(
			'post_type' => 'product',
			'orderby' => 'meta_order',
			'order' => 'ASC',
			'post_parent' => 0,
		);
		$products = Timber::get_posts( $args );
		$context['products'] = $products;

		
		return $context;
	}

	function add_to_twig( $twig ) {
		$twig->addFilter( new Twig_SimpleFilter('format_phone_number', array($this, 'format_phone_number')));
		$twig->addFunction( new Twig_SimpleFunction( 'sustainability_conversion', array( $this, 'sustainability_conversion' ) ) );
		return $twig;
	}

	// Formats 9 digit string into dot-notation phone number
	function format_phone_number( $number ) {
		$formattedNumber = sprintf("%s.%s.%s",
			substr($number, 0, 3),
			substr($number, 3, 3),
			substr($number, 6, 4));

		return $formattedNumber;
	}


	// Unit conversion for sustainability module
	public static function sustainability_conversion( $date, $rate ) {
		$origin = new DateTime($date);
		$target = new DateTime('now');
		$interval = $origin->diff($target);
		$totalHours = ($interval->y * 365 * 24) + ($interval->m * 30 * 24) + ($interval->d + 24) + $interval->h;
		// TODO: add initial number
		return $totalHours * $rate;
	}
}

new ModularSite();

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(

		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'General Settings',
		'menu_slug' 	=> 'general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}


// load application scripts
function app_scripts() {
	wp_enqueue_script('jquery');
  wp_enqueue_style('app-css', (get_template_directory_uri() . "/dist/application.css"), null, null);
  wp_enqueue_script('app-js', (get_template_directory_uri() . "/dist/application.js"), ['jquery'], null, true);
}

add_action('wp_enqueue_scripts', 'app_scripts');


// adds post type index
function app_get_types() {
    require_once(__DIR__ . '/types/post-types.php');
}

add_action('init', 'app_get_types');
// Allow Svg Upload In Wordpress Wp v4.7.1 and higher
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
  $filetype = wp_check_filetype( $filename, $mimes );
  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );
// relative paths
function switch_to_relative_url($html, $id, $caption, $title, $align, $url, $size, $alt)
{
	$imageurl = wp_get_attachment_image_src($id, $size);
	$relativeurl = wp_make_link_relative($imageurl[0]);   
	$html = str_replace($imageurl[0],$relativeurl,$html);
	      
	return $html;
}
add_filter('image_send_to_editor','switch_to_relative_url',10,8);

// removes default post editor
function remove_editor() {
  remove_post_type_support('page', 'editor');
}

add_action('admin_init', 'remove_editor');
