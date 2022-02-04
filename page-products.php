<?php

$context = Timber::get_context();

$post = new TimberPost();
$context['post'] = $post;

// TODO: we might not need this controller after all

Timber::render( array( 'page-products.twig' ), $context );