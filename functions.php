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
		$twig->addFunction( new Twig_SimpleFunction( 'the_breadcrumb', 'the_breadcrumb'  ) );
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
	// public static function sustainability_conversion( $date, $rate ) {
	// 	$past_deeds = 1613760000;
	// 	$origin = new DateTime($date);
	// 	$target = new DateTime('now');
	// 	$interval = $origin->diff($target);
	// 	$totalHours = ($interval->y * 365 * 24) + ($interval->m * 30 * 24) + ($interval->d + 24) + $interval->h;
	// 	// TODO: fix it's calculating per hour not per min
	// 	return $totalHours * $rate;
	// }

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

function remove_editor() {
  remove_post_type_support('page', 'editor');
}

add_action('admin_init', 'remove_editor');

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

/*=============================================
                BREADCRUMBS
=============================================*/
//  to include in functions.php
function the_breadcrumb()
{
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = '&raquo;'; // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb

    global $post;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
        }
    } else {
        echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
        } elseif (is_search()) {
            echo $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo $cats;
                if ($showCurrent == 1) {
                    echo $before . get_the_title() . $after;
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo $before . get_the_title() . $after;
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) {
                    echo ' ' . $delimiter . ' ';
                }
            }
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_tag()) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            echo $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</div>';
    }
} // end the_breadcrumb()
