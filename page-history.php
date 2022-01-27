<?php

$context = Timber::context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'page-history.twig', $context );
