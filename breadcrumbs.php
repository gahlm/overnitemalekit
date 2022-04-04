<?php

function breadcrumbs() {
	global $post;

  $spacer = '<li class="breadcrumbs__spacer">|</li>';

  // open list
  echo '<ul id="breadcrumbs" class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';

  // home page scrolled to product categories
  echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="/#homeProducts" title="products" itemprop="item">Products</a></li>';
  echo $spacer;

  if($post->post_parent) {
    // If child page, get parents
    $ancestors = get_post_ancestors($post->ID);
    
    // reverse for correct display order
    $ancestors = array_reverse($ancestors);

    // init var for page loop
    if (!isset($parents)) {
      $parents = null;
    }

    foreach($ancestors as $ancestor) {
      $parents .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '" itemprop="item">' . get_the_title($ancestor) . '</a></li>';
      $parents .= $spacer;
    }
    // parent pages
    echo $parents;
  }
  
  // current page
  echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span title="' . get_the_title() . '" itemprop="item">' . get_the_title() . '</span></li>';
  
  // close list
  echo '</ul>';
}