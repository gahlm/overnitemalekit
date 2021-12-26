<?php

$context = Timber::get_context();

$post = new TimberPost();
$context['post'] = $post;

$args = array(
  // Get post type project
  'post_type' => 'Product',
  // Get all posts
  'posts_per_page' => -1,
  // Order by post date
  'orderby' => array(
      'date' => 'DESC'
  )
);
$context['products'] = Timber::get_posts( $args );

Timber::render( array( 'page-products.twig' ), $context );