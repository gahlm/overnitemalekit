<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post();
$context['posts'] = Timber::get_posts();
$templates = array( 'index.twig' );

Timber::render( $templates, $context );