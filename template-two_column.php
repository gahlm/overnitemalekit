
<?php
/* Template Name: Two Column */

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'template-content-two-column.twig', $context );