<?php 
$view = "n";

if (is_front_page()) {	
	if (the_wmc('view_top_sub') == "y") {
		$view = "y";
	}
	
 } else if (is_home()) {

	if (the_wmc('view_sub') == "y") {
		$view = "y";
	}
	
} else if (is_singular()) {

	$sub_check = get_post_meta( $post->ID, "sub");

	if ($sub_check != false) {
		if ($sub_check[0] == "y") {
			$view = "y";
		} else if ($sub_check[0] == "def" && the_wmc('view_sub') == "y") {
			$view = "y";
		}
	} else if (the_wmc('view_sub') == "y") {
		$view = "y";
	}
} else if (the_wmc('view_sub') == "y") {
	$view = "y";
}

if ($view == "y") { ?>
	<div id="sub-contents" class="sub-column">
	<div class="sub-contents-btn">サブコンテンツ</div>
	<div id="sub-contents-in">
<?php
	dynamic_sidebar( 'sub-contents' );
?>
</div>
</div>
<?php
}
?>