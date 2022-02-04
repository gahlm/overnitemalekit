<?php

$context = Timber::get_context();

$post = new TimberPost();
$context['post'] = $post;

$args = array(
  'post_type' => 'product',
  'orderby' => 'menu_order',
  'post_parent' => 0,
);
$products = Timber::get_posts( $args );
$context['products'] = $products;

Timber::render( array( 'page-products.twig' ), $context );