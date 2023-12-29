<?php 
$view = "n";

if (is_front_page()) {
	if ((the_wmc('top_layout') == "def" && the_wmc('layout') == "col1") || (the_wmc('top_layout') == "col1")) {
		if ((the_wmc('view_top_side') == "def" && the_wmc('view_side') == "y") || (the_wmc('view_top_side') == "y")) $view = "y";
	} else {
		$view = "y";
	}

} else if (is_home()) {
	$post_page = get_option(page_for_posts);
	$page_layout = get_post_meta( $post_page, "page_layout");
	if (((empty($page_layout) || $page_layout[0] == "def") && the_wmc('layout') == "col1") || ($page_layout[0] == "col1")) {
		$side_bar_check = get_post_meta( $post_page, "side");
		if (the_wmc('view_side') == "y" && $side_bar_check[0] == "y") $view = "y";
	} else {
		$view = "y";
	}

} else if (get_post_type() == "lp") {
	$page_layout = get_post_meta( $post->ID, "page_layout");
	$side_bar_check = get_post_meta( get_the_ID(), "side");
	if (!isset($side_bar_check[0]) || $side_bar_check[0] == "y") $view = "y";

} else if (is_singular()) {
	$page_layout = get_post_meta( $post->ID, "page_layout");

	if (((empty($page_layout) || $page_layout[0] == "def") && the_wmc('layout') == "col1") || ($page_layout[0] == "col1")) {
		$side_bar_check = get_post_meta( $post->ID, "side");
		if (the_wmc('view_side') == "y" && $side_bar_check[0] == "y") $view = "y";
	} else {
		$view = "y";
	}

} else if (is_category()) {
	if ((the_wmc('list_category') == "def" && the_wmc('layout') == "col1") || (the_wmc('list_category') == "col1")) {
		if (the_wmc('view_side') == "y") $view = "y";
	} else {
		$view = "y";
	}

} else if (is_tag()) {
	if ((the_wmc('list_tag') == "def" && the_wmc('layout') == "col1") || (the_wmc('list_tag') == "col1")) {
		if (the_wmc('view_side') == "y") $view = "y";
	} else {
		$view = "y";
	}

} else if (is_date()) {
	if ((the_wmc('list_archive') == "def" && the_wmc('layout') == "col1") || (the_wmc('list_archive') == "col1")) {
		if (the_wmc('view_side') == "y") $view = "y";
	} else {
		$view = "y";
	}

} else if( is_author() ) {
	if ((the_wmc('list_author') == "def" && the_wmc('layout') == "col1") || (the_wmc('list_author') == "col1")) {
		if (the_wmc('view_side') == "y") $view = "y";
	} else {
		$view = "y";
	}

} else if( is_search()) {
	if ((the_wmc('list_search') == "def" && the_wmc('layout') == "col1") || (the_wmc('list_search') == "col1")) {
		if (the_wmc('view_side') == "y") $view = "y";
	} else {
		$view = "y";
	}

} else {
	if ((the_wmc('top_layout') == "def" && the_wmc('layout') == "col1") || (the_wmc('top_layout') == "col1")) {
		if (the_wmc('view_side') == "y") $view = "y";
	} else {
		$view = "y";
	}
}

if ($view == "y") { ?>
	<!--▼サブコンテンツ-->
	<aside class="sub-conts sidebar">
		<?php
			dynamic_sidebar( 'sidebar' );
		?>
	</aside>
	<!--▲サブコンテンツ-->
	
<?php
}
?>