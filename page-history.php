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
  'orderby' => array(
      'date' => 'DESC'
  )
);
$context['history'] = Timber::get_posts( $args );

Timber::render( array( 'page-history.twig' ), $context );