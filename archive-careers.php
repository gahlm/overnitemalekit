<?php

$context = Timber::get_context();

$post = new TimberPost();
$context['options'] = get_fields('options');

$args = array(
  // Get post type project
  'post_type' => 'careers',
  // Get all posts
  'posts_per_page' => -1,
  // Order by post date
  'orderby' => array(
      'date' => 'DESC'
  )
);
$context['careers'] = Timber::get_posts( $args );

Timber::render( array( 'page-careers.twig' ), $context );