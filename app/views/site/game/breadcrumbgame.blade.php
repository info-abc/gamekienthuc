<?php
	$breadcrumb = array(
		['name' => Type::find($game->type_main)->name, 'link' => url(Type::find($game->type_main)->slug)],
		['name' => 'Game ' . $game->name, 'link' => '']
	);
?>
@include('site.common.breadcrumb', $breadcrumb)