
   
<?php
/* Template Name: Product Page */

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'product.twig', $context );