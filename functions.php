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
			'post_type' => 'event',
			'posts_per_page' => -1,
			'meta_key' => 'year',
			'orderby'	=> 'meta_value',
			'order' => 'ASC'
		);
		$context['history'] = Timber::get_posts( $history );


		$facilities = array(
			'post_type' => 'Facility',
			'posts_per_page' => -1,
			'meta_key' => 'facility_name',
			'orderby'	=> 'meta_value',
			'order' => 'ASC'
		);
		$context['facilities'] = Timber::get_posts( $facilities );


		$products = array(
			'post_type' => 'product',
			'orderby' => 'meta_order',
			'order' => 'ASC',
			'post_parent' => 0,
		);
		$context['products'] = Timber::get_posts( $products );

        
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            $context['breadcrumbs'] = yoast_breadcrumb('<nav id="breadcrumbs" class="main-breadcrumbs">','</nav>', false );
        }
		
		return $context;
	}

	function add_to_twig( $twig ) {
		$twig->addFilter( new Twig_SimpleFilter('format_phone_number', array($this, 'format_phone_number')));
		$twig->addFunction( new Twig_SimpleFunction( 'my_breadcrumbs', 'my_breadcrumbs'  ) );
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
// relative paths
function switch_to_relative_url($html, $id, $caption, $title, $align, $url, $size, $alt)
{
	$imageurl = wp_get_attachment_image_src($id, $size);
	$relativeurl = wp_make_link_relative($imageurl[0]);   
	$html = str_replace($imageurl[0],$relativeurl,$html);
	      
	return $html;
}
add_filter('image_send_to_editor','switch_to_relative_url',10,8);


/*=============================================
                BREADCRUMBS
=============================================*/
function my_breadcrumbs() {
 
    /* === OPTIONS === */
    $text['home']     = 'Home'; // text for the 'Home' link
    $text['category'] = 'Archive by Category "%s"'; // text for a category page
    $text['search']   = 'Search Results for "%s" Query'; // text for a search results page
    $text['tag']      = 'Posts Tagged "%s"'; // text for a tag page
    $text['author']   = 'Articles Posted by %s'; // text for an author page
    $text['404']      = 'Error 404'; // text for the 404 page
 
    $show_current   = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
    $show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
    $show_title     = 1; // 1 - show the title for the links, 0 - don't show
    $delimiter      = ' &raquo; '; // delimiter between crumbs
    $before         = '<span class="current">'; // tag before the current crumb
    $after          = '</span>'; // tag after the current crumb
    /* === END OF OPTIONS === */
 
    global $post;
    $home_link    = home_url('/');
    $link_before  = '<span typeof="v:Breadcrumb">';
    $link_after   = '</span>';
    $link_attr    = ' rel="v:url" property="v:title"';
    $link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $parent_id    = $parent_id_2 = $post->post_parent;
    $frontpage_id = get_option('page_on_front');
 
    if (is_home() || is_front_page()) {
 
        if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';
 
    } else {
 
        echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
        if ($show_home_link == 1) {
            echo '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
            if ($frontpage_id == 0 || $parent_id != $frontpage_id) ;
        }
 
        if ( is_category() ) {
            $this_cat = get_category(get_query_var('cat'), false);
            if ($this_cat->parent != 0) {
                $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
 
        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;
 
        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;
 
        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;
 
        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;
 
        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->labels->name;
                printf($link, $home_link . $slug . '/', $post_type->labels->name);
                if ($show_current == 1) echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
                if ($show_current == 1) echo $before . get_the_title() . $after;
            }
 
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
 
        } elseif ( is_attachment() ) {
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
            $cats = str_replace('</a>', '</a>' . $link_after, $cats);
            if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
            echo $cats;
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
 
        } elseif ( is_page() && !$parent_id ) {
            if ($show_current == 1) echo $before . get_the_title() . $after;
 
        } elseif ( is_page() && $parent_id ) {
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $delimiter;
                }
            }
            if ($show_current == 1) {
                if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
                echo $before . get_the_title() . $after;
            }
 
        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
 
        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;
 
        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;
        }
 
        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }
 
        echo '</div><!-- .breadcrumbs -->';
    }
}