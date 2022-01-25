
<?php
/* Template Name: One Column */

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'template-content-one-column.twig', $context );