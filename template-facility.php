
   
<?php
/* Template Name: Facility Page */

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'template-facility.twig', $context );