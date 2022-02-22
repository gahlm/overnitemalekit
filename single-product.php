<?php

$context = Timber::get_context();
$post = Timber::query_post();
$context['product'] = $post;

$context['product_image'] = new Timber\Image($post->image);

$child_args = array(
  'post_type' => 'product',
  'orderby' => 'menu_order',
  'post_parent' => $post->id,
);
$children = Timber::get_posts( $child_args );
$context['children'] = $children;

Timber::render( 'single-product.twig', $context );