<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post();
$templates = array( 'home.twig' );
Timber::render( $templates, $context );
