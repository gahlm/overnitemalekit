<?php
/* Template Name: Modular */

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'modular.twig', $context );
