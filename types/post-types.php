<?php

$files = scandir( __DIR__  . '/' );

foreach ($files as $file) {
	if ( is_file( __DIR__  . '/' . $file ) && $file != '.DS_Store' ){
		require_once ( __DIR__  . '/' . $file );
	}
}