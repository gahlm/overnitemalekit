<?php

$context = Timber::get_context();

$post = new TimberPost();
$context['post'] = $post;

$args = array(
  // Get post type project
  'post_type' => 'event',
  // Get all posts
  'posts_per_page' => -1,
  // Order by post date
  'meta_key'			=> 'year',
	'orderby'			=> 'meta_value',
	'order'				=> 'ASC'
);
$context['history'] = Timber::get_posts( $args );

Timber::render( array( 'page-history.twig' ), $context );